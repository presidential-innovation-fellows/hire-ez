<?php

class Comments_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    // @security

    $this->filter('before', 'project_exists')->only(array('index'));

    $this->filter('before', 'vendor_exists')->only(array('vendor_index', 'vendor_create', 'vendor_destroy'));

    // $this->filter('before', 'i_am_collaborator');

    $this->filter('before', 'comment_exists')->only(array('destroy', 'vendor_destroy'));

    $this->filter('before', 'comment_is_mine')->only(array('destroy'));
  }

  public function action_index() {
    $view = View::make('comments.index');
    $view->project = Config::get('project');
    $view->stream_json = $view->project->stream_json();
    $this->layout->content = $view;

    Auth::user()->view_project_notifications_for_notification_type($view->project->id, "ProjectComment");
  }

  public function action_vendor_index() {
    $vendor = Config::get('vendor');
    return Response::json(Helper::models_to_array($vendor->get_comments()));
  }

  public function action_vendor_create() {
    $vendor = Config::get('vendor');

    $json = Input::json(true);

    // @placeholder some sort of check for commentable() actually existing
    $comment = new Comment(array('commentable_id' => $vendor->id,
                                 'commentable_type' => "vendor",
                                 'officer_id' => Auth::officer()->id,
                                 'body' => $json["body"]));

    $comment->save();

    $bids = $vendor->bids()->where_not_null('submitted_at')->get();

    foreach ($bids as $bid) {
      Notification::send("ApplicantComment", array('bid' => $bid, 'comment' => $comment, 'officer' => $comment->officer, 'project_id' => $bid->project_id));
    }

    $comment->commentable()->increment_comment_count();

    return Response::json($comment->to_array());
  }

  public function action_vendor_destroy() {
    $comment = Config::get('comment');

    $comment->commentable()->decrement_comment_count();

    $comment->delete();
    return Response::json(array('status' => 'success'));
  }

  public function action_create() {
    $json = Input::json();

    // @placeholder some sort of check for commentable() actually existing
    $comment = new Comment(array('commentable_id' => $json->commentable_id,
                                 'commentable_type' => $json->commentable_type,
                                 'officer_id' => Auth::officer()->id));
    $comment->body = $json->body;
    $comment->save();


    Notification::send("ProjectComment", array('comment' => $comment, 'officer' => $comment->officer, 'project' => $comment->commentable()));

    return Response::json($comment->to_array());

  }

  public function action_destroy() {
    $comment = Config::get('comment');
    $comment->delete();
    return Response::json(array('status' => 'success'));
  }

}

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::find($id);
  if (!$project) return Redirect::to('/');
  Config::set('project', $project);
});

Route::filter('vendor_exists', function() {
  $id = Request::$route->parameters[0];
  $vendor = Vendor::find($id);
  if (!$vendor) return Redirect::to('/');
  Config::set('vendor', $vendor);
});

Route::filter('i_am_collaborator', function() {
  $project = Config::get('project');
  if (!$project->is_mine() && !Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)) return Redirect::to('/');
});

Route::filter('comment_exists', function() {
  $id = Request::$route->parameters[1];
  $comment = Comment::where_id($id)
                    ->first();
  if (!$comment) return Redirect::to('/');
  Config::set('comment', $comment);
});

Route::filter('comment_is_mine', function() {
  $comment = Config::get('comment');
  if (!$comment->is_mine()) return Redirect::to('/');
});
