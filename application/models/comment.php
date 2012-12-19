<?php

class Comment extends SoftDeleteModel {

  public static $timestamps = true;

  public static $hidden = array();

  public $includes = array('officer');

  public $includes_in_array = array('formatted_created_at');

  public function commentable() {
    if ($this->commentable_type == "project") return Project::find($this->commentable_id);
    if ($this->commentable_type == "vendor") return Vendor::find($this->commentable_id);
    throw new Exception("Couldn't find that commentable type!");
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public function is_mine() {
    if (!Auth::officer()) return false;
    return (Auth::officer()->id == $this->officer->id) ? true : false;
  }

  public function formatted_created_at() {
    return date('c', is_object($this->created_at) ? $this->created_at->getTimestamp() : strtotime($this->created_at));
  }

}
