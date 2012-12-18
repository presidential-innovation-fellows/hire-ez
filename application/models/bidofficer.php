<?php

class BidOfficer extends Eloquent {

  public static $timestamps = true;

  public static $table = "bid_officer";

  public function bid() {
    return $this->belongs_to('Bid');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public static function mine_for_bid($bid_id) {
    if (!Auth::officer()) return false;

    $bid_officer = self::where_bid_id($bid_id)
                    ->where_officer_id(Auth::officer()->id)
                    ->first();

    if (!$bid_officer) {
      $bid_officer = new self(array('bid_id' => $bid_id, 'officer_id' => Auth::officer()->id));
    }

    return $bid_officer;
  }
}
