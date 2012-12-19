<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  // @placeholder
  // public static $accessible = array('company_name', 'contact_name', 'address', 'city', 'state', 'zip',
  //                                   'latitude', 'longitude', 'ballpark_price', 'more_info', 'homepage_url',
  //                                   'image_url', 'portfolio_url', 'sourcecode_url', 'duns');

  public $validator = false;

  public function validator() {
    if ($this->validator) return $this->validator;

    $rules = array('name' => 'required',
                   'email' => 'required',
                   'resume' => 'required',
                   'phone' => 'required',
                   // 'address' => 'required',
                   // 'city' => 'required',
                   // 'state' => 'required|max:2',
                   'zip' => 'required|numeric');

    $rules = array();

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function bids() {
    return $this->has_many('Bid')->where_null('deleted_at');
  }

  public function comments() {
    return Comment::where_commentable_type("vendor")->where_commentable_id($this->id);
  }

  public function get_comments() {
    return $this->comments()->get();
  }

  public function increment_comment_count() {
    $this->total_comments = $this->total_comments + 1;
    $this->save();
  }

  public function decrement_comment_count() {
    $this->total_comments = $this->total_comments - 1;
    $this->save();
  }

  public function ban() {
    $this->user->banned_at = new \DateTime;
    $this->user->save();

    foreach ($this->bids as $bid) {
      if (!$bid->awarded_at) $bid->delete();
    }
  }

}
