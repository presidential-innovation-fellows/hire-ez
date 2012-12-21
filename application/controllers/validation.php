<?php

class Validation_Controller extends Base_Controller {

  public function action_email() {
    $email = Input::get('user.email') ?: Input::get('vendor.email');
    $user = User::where_email($email)->first();
    if (!$user) $user = Vendor::where_email($email)->first();
    if ($user) {
      return Response::json("Sorry, we've already received an application from that email address.");
    } else {
      return Response::json(true);
    }
  }

}