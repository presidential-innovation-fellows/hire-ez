<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  // @placeholder
  // public static $accessible = array('company_name', 'contact_name', 'address', 'city', 'state', 'zip',
  //                                   'latitude', 'longitude', 'ballpark_price', 'more_info', 'homepage_url',
  //                                   'image_url', 'portfolio_url', 'sourcecode_url', 'duns');

  public static $hidden = array('demographic_survey_key', 'gender', 'race_1', 'race_2');

  public $validator = false;

  public $projects_applied_for = false;

  public function validator() {
    if ($this->validator) return $this->validator;

    $rules = array('name' => 'required',
                   'email' => $this->id ? 'required|email|unique:vendors,email,'.$this->id : 'required|email|unique:vendors',
                   'general_paragraph' => 'required',
                   'resume' => 'required',
                   'phone' => 'required',
                   'location' => 'required');

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
    $project_ids = $this->bids()->lists('project_id');

    return Project::where_in('id', $project_ids)
                   ->get();

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

  public function projects_applied_for() {
    if ($this->projects_applied_for !== false) return $this->projects_applied_for;
    return $this->projects_applied_for = $this->bids_with_project_names();
  }

  public function projects_not_applied_for() {
    return Helper::models_to_array(Project::where_not_in('id', $this->ids_of_projects_applied_for())->get());
  }

  public function titles_of_projects_applied_for() {
    return array_map(function($m){return $m->title;}, $this->projects_applied_for());
  }

  public function ids_of_projects_applied_for() {
    return array_map(function($m){return $m->id;}, $this->projects_applied_for());
  }

  public function ban() {
    $this->user->banned_at = new \DateTime;
    $this->user->save();

    foreach ($this->bids as $bid) {
      if (!$bid->awarded_at) $bid->delete();
    }
  }

  public static function surveyed() {
    return self::where_null('demographic_survey_key');
  }

  public static function search_race($q) {
    return self::where('race_1', '=', $q)->or_where('race_2', '=', $q);
  }

}

Event::listen('eloquent.created: Vendor', function($model) {
  $model->demographic_survey_key = Str::random(12);
  $model->save();
});
