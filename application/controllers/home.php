    <?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::guest()) return Redirect::to_route('new_vendors');
    if (Auth::user()->officer) return Redirect::to_route('my_projects');
    //$view = View::make('home.index_signed_out');
    //$view->projects = Project::get();
    //$this->layout->content = $view;
  }

}