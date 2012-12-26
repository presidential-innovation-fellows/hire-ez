<?php

class Vendors_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
    $this->filter('before', 'officer_only')->only(array('index', 'show', 'get_more'));
    $this->filter('before', 'vendor_exists')->only(array('show'));
    $this->filter('before', 'survey_key_exists')->only(array('applied', 'applied_post'));
  }

  public function action_new() {
    $view = View::make('vendors.new');
    $view->projects = Project::open_projects()->get();
    $this->layout->content = $view;
  }

  public function action_create() {
    $vendor = new Vendor(Input::get('vendor'));
    $vendor->general_paragraph = nl2br(Input::get('vendor.general_paragraph'));
    if ($vendor->validator()->passes()) {
      $vendor->demographic_survey_key = Str::random(12);
      $vendor->save();
      $applications = Input::get('apply');
      $whygood = Input::get('whygood');

      foreach($applications as $proj_id => $val) {
        if ($whygood[$proj_id] !== '') {
          $bits = explode('-', $proj_id);
          $project_id = intval(array_pop($bits));
          $bid = new Bid();
          $bid->vendor_id = $vendor->id;
          $bid->project_id = $project_id;
          $bid->body = nl2br($whygood[$proj_id]);

          if ($bid->validator()->passes()) {
            $bid->submit();
          } else {
            Session::flash('errors', $bid->validator()->errors->all());
            return Redirect::to_route('new_bids', array($project->id, $bid->id))->with_input();
          }
        }
      }
      //@placeholder
      //Mailer::send("NewVendorRegistered", array("user" => $user));
      return Redirect::to_route('vendor_applied', $vendor->demographic_survey_key);
    } else {
      Session::flash('errors', $vendor->validator()->errors->all());
      return Redirect::to_route('new_vendors')->with_input();
    }
  }

  public function action_index() {
    $view = View::make('vendors.index');
    $view->projects = Project::get();
    $this->layout->content = $view;
  }

  public function action_applied($key) {
    $view = View::make('vendors.applied');
    $view->vendor = Config::get('vendor');
    $this->layout->content = $view;
  }

  public function action_applied_post($key) {
    $vendor = Config::get('vendor');

    $race_input = Input::get('race');

    $vendor->demographic_survey_key = null;
    $vendor->gender = Input::get('gender');
    $vendor->race_1 = @$race_input[0];
    $vendor->race_2 = @$race_input[1];

    $vendor->save();

    return Redirect::to_route('survey_thanks');
  }

  public function action_survey_thanks() {
    $view = View::make('vendors.survey_thanks');
    $this->layout->content = $view;
  }

  public function action_get_more() {
    $page = Input::get('page');
    $project = Project::find(Input::get('project_id'));
    $vendors = $project->top_unhired_applicants()->take(10)->skip(($page - 1) * 10)->get();

    $more = $project->top_unhired_applicants()->count() > ($page * 10) ? true : false;

    return Response::json(array(
                      'html' => View::make('vendors.partials.applicants_trs')->with('applicants', $vendors)->render(),
                      'more' => $more
                    ));
  }

  public function action_show() {
    $view = View::make('vendors.show');
    $view->vendor = Config::get('vendor');
    $this->layout->content = $view;
    Auth::user()->view_notification_payload("vendor", $view->vendor->id);
  }

}

Route::filter('survey_key_exists', function(){
  $key = Request::$route->parameters[0];
  if (!$key) return Response::error('404');
  $vendor = Vendor::where_demographic_survey_key($key)->first();
  if (!$vendor) return Response::error('404');
  Config::set('vendor', $vendor);
});

Route::filter('vendor_exists', function() {
  $id = Request::$route->parameters[0];
  $vendor = Vendor::find($id);
  if (!$vendor) return Redirect::to('/vendors');
  Config::set('vendor', $vendor);
});