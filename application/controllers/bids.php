<?php

class Bids_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'vendor_only')->only(array('new', 'create', 'mine', 'destroy'));

    $this->filter('before', 'project_exists')->except(array('mine'));

    $this->filter('before', 'i_am_collaborator')->only(array('review', 'update', 'transfer', 'show'));

    $this->filter('before', 'bid_exists')->only(array('update', 'transfer', 'show'));

    $this->filter('before', 'bid_is_submitted_and_not_deleted')->only(array('update', 'transfer', 'show'));

    $this->filter('before', 'i_have_not_already_bid')->only(array('new', 'create'));
  }

  public $bid_sort_options = array('score' => 'total_score',
                                   'name' => 'vendors.name',
                                   'unread' => 'bid_officer.read',
                                   'comments' => 'vendors.total_comments');


  // review page
  public function action_review($project_id, $filter = "") {
    $view = View::make('bids.review');
    $view->query = Input::get('q');
    $view->project = Config::get('project');
    if ($filter) Config::set('review_bids_filter', $filter);

    $sort = @$this->bid_sort_options[Input::get('sort')] ?: false;
    $order = Input::get('order');

    if ($filter == 'hired') {
      $q = $view->project->winning_bids();
    } elseif ($filter == 'starred') {
      $q = $view->project->starred_bids();
    } elseif ($filter == 'thumbs-downed') {
      $q = $view->project->thumbs_downed_bids();
    } elseif ($filter == 'spam') {
      $q = $view->project->dismissed_bids();
    } else {
      $q = $view->project->submitted_bids();
    }

    if ($view->query) {
      $q = $q->where(function($q)use($view){
        $q->or_where('name', 'LIKE', '%'.$view->query.'%');
        $q->or_where('body', 'LIKE', '%'.$view->query.'%');
      });
    }

    if ($sort) $q = $q->order_by($sort, $order);

    $appends = array();
    if (Input::get('sort')) $appends["sort"] = Input::get('sort');
    if (Input::get('order')) $appends["order"] = Input::get('order');

    $view->bids = $q->paginate(50);
    $view->links = $view->bids->appends($appends)->links();

    $view->bids_json = eloquent_to_json($view->bids->results);
    $this->layout->content = $view;
  }

  // "transfer" a bid to another project
  // doesn't actually remove the bid from your project,
  // just clones it and adds it to another project.
  public function action_transfer() {
    $bid = Config::get('bid');
    $project = Config::get('project');
    $from_email = Auth::officer()->user->email;
    $transfer_to_project = Project::find(Input::get('project_id'));

    if (!$transfer_to_project) {
      Session::flash('error', "Couldn't find the project that you're trying to transfer this bid to.");
      return Redirect::to_route('bid', array($project->id, $bid->id));
    }

    $new_bid = new Bid(array('body' => "Transferred from project $project->title by $from_email.",
                             'project_id' => $transfer_to_project->id));

    $new_bid->vendor_id = $bid->vendor_id;
    $new_bid->submitted_at = new \DateTime;

    $new_bid->save();

    Session::flash('notice', "Success! Transferred the bid from ".$bid->vendor->name." to ".$transfer_to_project->title.".");
    return Redirect::back();

  }

  // handle updates from backbone
  public function action_update() {
    $bid = Config::get('bid');
    $input = Input::json(true);

    $bid->set_officer_read($input["read"]);
    $bid->set_officer_starred($input["starred"]);
    $bid->set_officer_thumbs_downed($input["thumbs_downed"]);
    $bid->set_dismissed($input["dismissed_at"]);
    $bid->set_awarded($input["awarded_at"]);

    $bid->bid_officer()->save();
    $bid->sync_anyone_read($input["read"]);
    $bid->save();

    $bid = Bid::with_officer_fields()
              ->where('bids.id', '=', $bid->id)
              ->first();

    return Response::json($bid->to_array());
  }

  // handle updates from backbone
  public function action_show() {
    $bid = Config::get('bid');

    $bid = Bid::with_officer_fields()
              ->where('bids.id', '=', $bid->id)
              ->first();

    $bid->vendor->includes_in_array = array('titles_of_projects_applied_for', 'ids_of_projects_applied_for', 'projects_not_applied_for');


    return Response::json($bid->to_array());
  }

  // new bid page
  public function action_new() {
    $view = View::make('bids.new');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  // create bid
  public function action_create() {
    $project = Config::get('project');
    $bid = $project->my_current_bid_draft() ?: new Bid();
    $bid->vendor_id = Auth::user()->vendor->id;
    $bid->project_id = $project->id;

    $bid_input = array_map(function($t){return nl2br($t);}, Input::get('bid'));
    $bid->fill($bid_input);

    $prices = array();
    $i = 0;
    $deliverable_prices = Input::get('deliverable_prices');
    foreach (Input::get('deliverable_names') as $deliverable_name) {
      if (trim($deliverable_name) !== "") {
        $prices[$deliverable_name] = $deliverable_prices[$i];
      }
      $i++;
    }
    $bid->prices = $prices;

    if (Input::get('submit_now') === 'true') {
      if ($bid->validator()->passes()) {
        $bid->sync_with_epls();
        $bid->submit();
        Session::flash('notice', __("r.flashes.bid_submitted"));
        return Redirect::to_route('bid', array($project->id, $bid->id));
      } else {
        Session::flash('errors', $bid->validator()->errors->all());
        return Redirect::to_route('new_bids', array($project->id, $bid->id))->with_input();
      }
    } else {
      $bid->save();
      return Response::json(array("status" => "success"));
    }

  }

  // vendor deletes bid -- @placeholder remove this?
  public function action_destroy() {
    $project = Config::get('project');
    $bid = Config::get('bid');
    $bid->delete_by_vendor();
    return Redirect::to_route('project', array($project->id));
  }

}

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::find($id);
  if (!$project) return Redirect::to('/');
  Config::set('project', $project);
});

Route::filter('i_am_collaborator', function() {
  $project = Config::get('project');
  if (!$project->is_mine()) return Redirect::to('/');
});

Route::filter('bid_exists', function() {
  $id = Request::$route->parameters[1];
  $bid = Bid::with_officer_fields()
            ->where('bids.id', '=', $id)
            ->first();

  if (!$bid) return $id;
  Config::set('bid', $bid);
});

Route::filter('bid_is_submitted_and_not_deleted', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if (!$bid->submitted_at || $bid->deleted_at) return Redirect::to_route('review_bids', array($project->id));
});

Route::filter('bid_is_not_awarded', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if ($bid->awarded_at) return Redirect::to_route('project', array($project->id));
});

Route::filter('i_am_collaborator_or_bid_vendor', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if (!$bid->is_mine() && !$project->is_mine()) return Redirect::to('/');
});

Route::filter('i_am_contracting_officer', function() {
  if (!Auth::officer()->is_verified_contracting_officer()) return Redirect::to('/');
});

Route::filter('i_am_bid_vendor', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if (!$bid->is_mine()) return Redirect::to('/');
});

Route::filter('i_have_not_already_bid', function() {
  $project = Config::get('project');
  $bid = $project->current_bid_from(Auth::vendor());

  if ($bid) {
    Session::flash('notice', __("r.flashes.already_bid"));
    return Redirect::to_route('project', array($project->id));
  }
});

Route::filter('project_has_not_already_been_awarded', function() {
  $project = Config::get('project');
  if ($project->winning_bid())
    return Redirect::to_route('project', array($project->id))->with('errors', array(__("r.flashes.already_awarded")));
});

Route::filter('bid_has_not_been_dismissed_or_awarded', function(){
  $bid = Config::get('bid');
  if ($bid->awarded_at || $bid->dismissed_at) return Redirect::to_route('bid', array($bid->project->id, $bid->id));
});
