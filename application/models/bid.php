<?php

class Bid extends SoftDeleteModel {

  public static $timestamps = true;

  public $includes = array('vendor', 'vendor.user');

  public $bid_officer = false;

  public static $accessible = array('project_id', 'body'); // @placeholder

  public function validator() {
    // @placeholder
    $rules = array('body' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

  public function project() {
    return $this->belongs_to('Project');
  }

  public function comments() {
    return Comment::where_commentable_type("bid")->where_commentable_id($this->id);
  }

  public function get_comments() {
    return $this->comments()->get();
  }

  public function is_mine() {
    return (Auth::vendor() && ($this->vendor->id == Auth::vendor()->id)) ? true : false;
  }

  public function dismiss($reason = false, $explanation = false) {
    $this->dismissed_at = new \DateTime;
    $this->save();

    Notification::send('Dismissal', array('bid' => $this, 'actor_id' => Auth::user() ? Auth::user()->id : null));
  }

  public function undismiss() {
    $this->dismissed_at = NULL;
    $this->save();
    Notification::send('Undismissal', array('bid' => $this, 'actor_id' => Auth::user() ? Auth::user()->id : null));
  }

  public function dismissed() {
    return $this->dismissed_at ? true : false;
  }

  public function get_status() {
    if (!$this->submitted_at) {
      return "Draft Saved";
    } elseif ($this->dismissed()) {
      return "Dismissed";
    } elseif ($this->awarded_at) {
      return "Won!";
    } else {
      return "Pending Review";
    }
  }

  public function submit() {
    $this->submitted_at = new \DateTime;
    $this->save();

    foreach ($this->project->officers as $officer) {
      Notification::send('BidSubmit', array('bid' => $this, 'target_id' => $officer->user_id));
    }
  }

  public function award() {
    $this->awarded_at = new \DateTime;
    $this->awarded_by = Auth::officer()->id;
    $this->save();

    Notification::send("Award", array('actor_id' => Auth::user()->id, 'bid' => $this));

    // Dismiss all the other bids.
    foreach ($this->project->bids as $bid) {
      if ($bid->id != $this->id && !$bid->dismissed_at)
        $bid->dismiss();
    }
  }

  public function delete_by_vendor() {
    $this->delete();

    Notification::where_payload_type("bid")->where_payload_id($this->id)->delete();
  }

  public function bid_officer() {
    if ($this->bid_officer !== false) return $this->bid_officer;
    return $this->bid_officer = BidOfficer::mine_for_bid($this->id);
  }

  public function has_read_bid_officer() {
    return BidOfficer::read_for_bid($this->id) ? true : false;

  }

  public function set_officer_read($read) {
    $bid_officer = $this->bid_officer();

    if ($bid_officer->read == $read) return;

    if ($read && !$this->anyone_read) {
      $this->anyone_read = true;
      $this->save();
    }

    $bid_officer->read = $read;
  }

  public function set_officer_starred($starred) {
    $bid_officer = $this->bid_officer();

    if ($starred !== null && $bid_officer->starred != $starred) {
      $this->total_stars = $this->total_stars + ($starred ? 1 : -1);
      $this->save();
    }

    $bid_officer->starred = $starred ? true : false;
  }

  public function set_officer_thumbs_downed($thumbs_downed) {
    $bid_officer = $this->bid_officer();

    if ($thumbs_downed !== null && $bid_officer->thumbs_downed != $thumbs_downed) {
      $this->total_thumbs_down = $this->total_thumbs_down + ($thumbs_downed ? 1 : -1);
      $this->save();
    }

    $bid_officer->thumbs_downed = $thumbs_downed ? true : false;
  }

  public function sync_anyone_read($read) {
    if (!$read && $this->anyone_read && !$this->has_read_bid_officer()) {
      $this->anyone_read = false;
    }
  }

  public function set_dismissed($dismissed) {
    if ($this->dismissed_at && $dismissed) return;
    if (!$this->dismissed_at && !$dismissed) return;

    $this->dismissed_at = $dismissed ? new \DateTime : null;
    $this->save();
  }

  public function set_awarded($awarded) {
    if ($this->awarded_at && $awarded) return;
    if (!$this->awarded_at && !$awarded) return;

    if ($awarded) {
      $this->awarded_at = new \DateTime;
      $this->awarded_by = Auth::officer()->id;
    } else {
      $this->awarded_at = null;
      $this->awarded_by = null;
    }

    $this->save();
  }

  public function increment_comment_count() {
    $this->total_comments = $this->total_comments + 1;
    $this->save();
  }

  public function decrement_comment_count() {
    $this->total_comments = $this->total_comments - 1;
    $this->save();
  }

  public static function with_officer_fields() {
    return self::left_join('bid_officer', 'bid_id', '=', 'bids.id')
               ->left_join('vendors', 'vendor_id', '=', 'vendors.id')
                ->where(function($query){
                  $query->where_null('bid_officer.officer_id');
                  $query->or_where('bid_officer.officer_id', '=', Auth::officer()->id);
                })
                ->select(array('*',
                               'bids.id as id', 'bids.created_at as created_at',
                               'bids.updated_at as updated_at',
                               DB::raw('(`bids`.`total_stars` - `bids`.`total_thumbs_down`) as `total_score`')))
                ->where_null('bids.deleted_at');
  }

}

