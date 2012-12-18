<?php

class Project extends Eloquent {

  const STATUS_ACCEPTING_BIDS = 1;
  const STATUS_REVIEWING_BIDS = 2;
  const STATUS_CONTRACT_AWARDED = 3;

  public static $timestamps = true;

  public static $my_project_ids = false;

  // @placeholder
  // public static $accessible = array('project_type_id', 'title', 'agency', 'office', 'public', 'background',
  //                                   'sections', 'variables', 'proposals_due_at', 'price_type');

  public $winning_bid = false;

  public $validator = false;


  public function validator() {
    if ($this->validator) return $this->validator;

    // @placeholder
    // $rules = array('title' => 'required',
    //                'project_type_id' => 'required');
    $rules = array();

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function officers() {
    return $this->has_many_and_belongs_to('Officer', 'project_collaborators')->with(array('owner'))->order_by('owner', 'desc');
  }

  public function comments() {
    return Comment::where_commentable_type("project")->where_commentable_id($this->id);
  }

  public function get_comments() {
    return $this->comments()->get();
  }

  public function owner() {
    return $this->officers()->where_owner(true)->first();
  }

  public function i_am_owner() {
    return (Auth::officer() && Auth::officer()->id == $this->owner()->id) ? true : false;
  }

  public function top_unhired_applicants() {
    return $this->bids(true)
                ->where_null('awarded_at')
                ->order_by('total_stars', 'desc');
  }

  public function bids($no_extra_fields = false) {
    if (!Auth::officer() || $no_extra_fields) {
      return $this->has_many('Bid')->where_null('deleted_at');
    } else {

      return $this->has_many('Bid')
                  ->left_join('bid_officer', 'bid_id', '=', 'bids.id')
                  ->left_join('vendors', 'vendor_id', '=', 'vendors.id')
                  ->where(function($query){
                    $query->where_null('bid_officer.officer_id');
                    $query->or_where('bid_officer.officer_id', '=', Auth::officer()->id);
                  })
                  ->select(array('*',
                                 'bids.id as id',
                                 'bids.created_at as created_at',
                                 'bids.updated_at as updated_at',))
                  ->where_null('bids.deleted_at');

    }
  }

  public function winning_bids() {
    return $this->bids()->where_not_null('awarded_at');
  }

  public function starred_bids() {
    return $this->bids()->where('total_stars', '>', 0);
  }

  public function is_mine() {
    if (!Auth::user() || !Auth::user()->officer) return false;
    if (self::$my_project_ids === false)
      self::$my_project_ids = ProjectCollaborator::where_officer_id(Auth::officer()->id)
                                                 ->lists('project_id');

    if (in_array($this->id, self::$my_project_ids))
      return true;

    return false;
  }

  public function my_bid() {
    if (!Auth::user() || !Auth::user()->vendor) return false;

    if ($bid = Auth::user()->vendor->bids()
                           ->where_project_id($this->id)
                           ->first()) {
      return $bid;
    }

    return false;
  }

  public function status() {
    if (new DateTime($this->proposals_due_at, new DateTimeZone('UTC')) > new DateTime('', new DateTimeZone('UTC')) && !$this->winning_bids()->first()) {
      return self::STATUS_ACCEPTING_BIDS;
    } elseif (!$this->winning_bids()->first()) {
      return self::STATUS_REVIEWING_BIDS;
    } else {
      return self::STATUS_CONTRACT_AWARDED;
    }
  }

  public function is_open_for_bids() {
    return $this->status() == self::STATUS_ACCEPTING_BIDS;
  }

  public function status_text() {
    return self::status_to_text($this->status());
  }

  public static function status_to_text($status) {
    switch ($status) {
      case self::STATUS_ACCEPTING_BIDS:
        return "Accepting bids";
      case self::STATUS_REVIEWING_BIDS:
        return "Reviewing bids";
      case self::STATUS_CONTRACT_AWARDED:
        return "Contract Awarded";
    }
  }

  public function current_bid_from($vendor) {
    $bid = Bid::where('project_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where_not_null('submitted_at')
              ->first();

    return $bid ? $bid : false;
  }

  public function current_bid_draft_from($vendor) {
    $bid = Bid::where('project_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where_null('submitted_at')
              ->first();

    return $bid ? $bid : false;
  }

  public function my_current_bid() {
    if (!Auth::user() || !Auth::user()->vendor) return false;
    return $this->current_bid_from(Auth::user()->vendor);
  }

  public function my_current_bid_draft() {
    if (!Auth::user() || !Auth::user()->vendor) return false;
    return $this->current_bid_draft_from(Auth::user()->vendor);
  }

  public function submitted_bids() {
    return $this->bids()
                ->where_not_null('submitted_at');
  }

  public function open_bids() {
    return $this->submitted_bids()
                ->where_null('dismissed_at')
                ->where_null('awarded_at');
  }

  public function dismissed_bids() {
    return $this->submitted_bids()
                ->where_not_null('dismissed_at');
  }

  public function notifications() {
    // notifications that are about this project, its bids,
    // or its collaborators.
    $project = $this;

    return Notification::where(function($query){
                    $query->where('payload_type', '=', 'bid');
                    $query->where_in('payload_id', $this->bids()->lists('id') ?: array(''));
                })->or_where(function($query)use($project){
                  $query->where('payload_type', '=', 'project');
                  $query->where('payload_id', '=', $project->id);
                })
                ->get();
  }

  public function stream_json($json = true) {
    $comments = array_map(function($m) { return $m->to_array(); }, $this->get_comments());
    $notifications = array_map(function($m) { return $m->to_array(); }, $this->notifications());

    $return_array = array_merge($comments, $notifications);

    usort($return_array, function($a, $b){
      // oldest first
      return $a["created_at"] > $b["created_at"];
    });

    // this could get unruly with a really big project
    return $json ? json_encode($return_array) : $return_array;
  }

  //////////// STATIC FUNCTIONS ////////////

  public static function open_projects() {
    return self::where('proposals_due_at', '>', new \DateTime("", new DateTimeZone('UTC')));
  }

}
