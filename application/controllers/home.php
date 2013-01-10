<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    $view = View::make('home.index_signed_out');
    $view->projects = Project::get();
    $this->layout->content = $view;
  }

}