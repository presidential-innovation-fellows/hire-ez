<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::check()) {
      if (Auth::user()->officer) {
        Session::reflash();
        return Redirect::to_route('my_projects');
      } else {
        Session::reflash();
        return Redirect::to_route('projects');
      }
    } else {
      $view = View::make('home.index_signed_out');
    }
    $view->projects = Project::open_projects()->get();
    $this->layout->content = $view;
  }

}