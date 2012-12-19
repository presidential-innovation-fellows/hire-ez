<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  // @placeholder
  // public static $accessible = array('company_name', 'contact_name', 'address', 'city', 'state', 'zip',
  //                                   'latitude', 'longitude', 'ballpark_price', 'more_info', 'homepage_url',
  //                                   'image_url', 'portfolio_url', 'sourcecode_url', 'duns');

  public $validator = false;

  public $includes_in_array = array('list_names_of_projects_applied_for');

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

  public function bids_with_project_names() {
    return $this->has_many('Bid')
                ->left_join('projects', 'project_id', '=', 'projects.id')
                ->select(array('*',
                               'bids.id as id',
                               'bids.body as body',
                               'bids.created_at as created_at',
                               'bids.updated_at as updated_at'))
                ->where_null('bids.deleted_at');
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

  public function list_names_of_projects_applied_for() {
    return implode(", ", $this->bids_with_project_names()->lists('title'));
  }

  public function ban() {
    $this->user->banned_at = new \DateTime;
    $this->user->save();

    foreach ($this->bids as $bid) {
      if (!$bid->awarded_at) $bid->delete();
    }
  }

}
