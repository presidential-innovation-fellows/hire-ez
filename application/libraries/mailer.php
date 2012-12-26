<?php

Class Mailer {

  public static function send($template_name, $attributes = array()) {

    $message = Swift_Message::newInstance();
    $message->setFrom(array(Config::get('mailer.from.email')=>Config::get('mailer.from.name')));

    if ($template_name == "Notification") {
      $notification = $attributes["notification"];
      $officer = $attributes["officer"];
      $parsed = $notification->parsed();

      $message->setSubject($parsed["subject"])
              ->setTo($officer->user->email)
              ->addPart(View::make('mailer.notification_text')->with('notification', $notification), 'text/plain')
              ->setBody(View::make('mailer.notification_html')->with('notification', $notification), 'text/html');

    } elseif ($template_name == "NewOfficerInvited") {
      $invited_by = $attributes["invited_by"];
      $new_user = $attributes["new_user"];
      $project = $attributes["project"];

      $message->setSubject("You've been invited to collaborate on " . __('r.app_name') .  " by ".$invited_by->email)
              ->setTo($new_user->email)
              ->addPart(View::make('mailer.new_officer_invited_text')
                ->with('new_user', $new_user)
                ->with('invited_by', $invited_by)
                ->with('project', $project), 'text/plain')
              ->setBody(View::make('mailer.new_officer_invited_html')
                ->with('new_user', $new_user)
                ->with('invited_by', $invited_by)
                ->with('project', $project), 'text/html');

    } elseif ($template_name == "FinishOfficerRegistration") {
      $user = $attributes["user"];

      $message->setSubject("Complete your " . __('r.app_name') . " Registration")
              ->setTo($user->email)
              ->addPart(View::make('mailer.finish_officer_registration_text')->with('user', $user), 'text/plain')
              ->setBody(View::make('mailer.finish_officer_registration_html')->with('user', $user), 'text/html');

    } elseif ($template_name == "ForgotPassword") {
      $user = $attributes["user"];

      $message->setSubject(__('r.app_name') . " Reset Password Request")
              ->setTo($user->email)
              ->addPart(View::make('mailer.forgot_password_text')->with('user', $user), 'text/plain')
              ->setBody(View::make('mailer.forgot_password_html')->with('user', $user), 'text/html');

    } elseif ($template_name == "ApplicationReceived") {
      $vendor = $attributes["vendor"];

      $message->setSubject("Your application has been received.")
              ->setTo($vendor->email)
              ->addPart(View::make('mailer.application_received_text')->with('vendor', $vendor), 'text/plain')
              ->setBody(View::make('mailer.application_received_html')->with('vendor', $vendor), 'text/html');

    } else {
      throw new \Exception("Can't find the template you specified.");
    }

    foreach ($message->getChildren() as $child) {
      if ($child->getContentType() == 'text/plain') {
        $text_part = $child;
      }
    }

    $message_display_array = array('to' => "".$message->getHeaders()->get('To'),
                                   'subject' => $message->getSubject(),
                                   'html' => "".$message->getBody(),
                                   'text' => "".$text_part);


    // If mailer.send_all_to is set in the config files, ignore the original
    // recipient and instead, send to the email address specified.
    if (Config::has('mailer.send_all_to')) {
      $message->setSubject("(".$message->getHeaders()->get('To').") ".$message->getSubject());
      $message->setTo(Config::get('mailer.send_all_to'));
    }

    Log::info("SENDING EMAIL:");
    Log::info(print_r($message_display_array, true));

    $transport = Config::get('mailer.transport');
    if (!$transport) return;
    $mailer = Swift_Mailer::newInstance($transport);

    $mailer->send($message);

  }

}