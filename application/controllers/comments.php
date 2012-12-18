<?php

class Comments_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    // @security

    $this->filter('before', 'project_exists')->only(array('index'));

    $this->filter('before', 'bid_exists')->only(array('bid_index', 'bid_create', 'bid_destroy'));

    // $this->filter('before', 'i_am_collaborator');

    $this->filter('before', 'comment_exists')->only(array('destroy', 'bid_destroy'));

    $this->filter('before', 'comment_is_mine')->only(array('destroy'));
  }

  public function action_index() {
    $view = View::make('comments.index');
    $view->project = Config::get('project');
    $view->stream_json = $view->project->stream_json();
    $this->layout->content = $view;

    $comment_ids = array();
    foreach($view->project->get_comments() as $comment) $comment_ids[] = $comment->id;
    Auth::user()->view_notification_payload("comment", $comment_ids, "read");
  }

  public function action_bid_index() {
    $bid = Config::get('bid');
    return Response::json(Helper::models_to_array($bid->get_comments()));
  }

  public function action_bid_create() {
    $bid = Config::get('bid');

    $json = Input::json(true);

    // @placeholder some sort of check for commentable() actually existing
    $comment = new Comment(array('commentable_id' => $bid->id,
                                 'commentable_type' => "bid",
                                 'officer_id' => Auth::officer()->id,
                                 'body' => $json["body"]));

    $comment->save();

    $comment->commentable()->increment_comment_count();

    return Response::json($comment->to_array());
  }

  public function action_bid_destroy() {
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


    foreach($comment->commentable()->officers as $officer) {
      if (Auth::officer()->id != $officer->id)
        Notification::send("Comment", array('comment' => $comment,
                                            'target_id' => $officer->user->id));
    }

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

Route::filter('bid_exists', function() {
  $id = Request::$route->parameters[0];
  $bid = Bid::find($id);
  if (!$bid) return Redirect::to('/');
  Config::set('bid', $bid);
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
