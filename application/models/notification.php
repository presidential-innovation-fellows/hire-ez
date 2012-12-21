<?php

class Notification extends Eloquent {

  public static $timestamps = true;

  public $includes_in_array = array('parsed', 'formatted_created_at');

  public function target() {
    return $this->belongs_to('User', 'target_id');
  }

  public function actor() {
    return $this->belongs_to('User', 'actor_id');
  }

  public function notification_officer() {
    return NotificationOfficer::mine_for_notification($this->id);
  }

  public function get_payload() {
    return json_decode($this->attributes['payload'], true);
  }

  public function set_payload($value) {
    $this->attributes['payload'] = json_encode($value);
  }

  public function mark_as_read() {
    Log::info('mark as read');
    $notification_officer = $this->notification_officer();
    $notification_officer->read = true;
    $notification_officer->save();
  }

  public function mark_as_unread() {
    $notification_officer = $this->notification_officer();
    $notification_officer->read = false;
    $notification_officer->save();
  }

  public function parsed() {
    return NotificationParser::parse($this);
  }

  public function formatted_created_at() {
    return date('c', is_object($this->created_at) ? $this->created_at->getTimestamp() : strtotime($this->created_at));
  }

  public static function send($notification_type, $attributes, $send_email = true) {
    $notification = new Notification(array('notification_type' => $notification_type,
                                           'actor_id' => Auth::officer()->user->id));

    /*
      Notification Types:

        - Applicant hired
        - Applicant comment
        - Project comment
        - Applicant forwarded to you
        - Collaborator added
    */

    if ($notification->notification_type == "ApplicantHired") {
      $notification->fill(array('payload' => array('bid' => $attributes["bid"]->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $attributes["bid"]->id,
                                'project_id' => $attributes["bid"]->project->id));

    } elseif ($notification->notification_type == "ApplicantComment") {
      $notification->fill(array('payload' => array('vendor' => $attributes["bid"]->vendor->to_array(), 'officer' => $attributes["officer"]->to_array(), 'comment' => $attributes["comment"]->to_array()),
                                'payload_type' => 'vendor',
                                'payload_id' => $attributes["bid"]->vendor_id,
                                'project_id' => $attributes["project_id"]));

    } elseif ($notification->notification_type == "ProjectComment") {
      $notification->fill(array('payload' => array('comment' => $attributes["comment"]->to_array(), 'officer' => $attributes["officer"]->to_array(), 'project' => $attributes["project"]->to_array()),
                                'payload_type' => 'comment',
                                'payload_id' => $attributes["comment"]->id,
                                'project_id' => $attributes["project"]->id));

    } elseif ($notification->notification_type == "ApplicantForwarded") {
      $notification->fill(array('payload' => array('bid' => $attributes["bid"]->to_array(), 'project' => $attributes["project"]->to_array(), 'from_project' => $attributes["from_project"]->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $attributes["bid"]->id,
                                'project_id' => $attributes["project"]->id));

    } elseif ($notification->notification_type == "CollaboratorAdded") {
      $notification->fill(array('payload' => array('officer' => $attributes["officer"]->to_array(), 'project' => $attributes["project"]->to_array()),
                                'payload_type' => 'officer',
                                'payload_id' => $attributes["officer"]->id,
                                'project_id' => $attributes["project"]->id));

    } else {
      throw new \Exception("Don't know how to handle that notification type.");
    }

    $notification->save();
    if ($send_email) Mailer::send("Notification", array('notification' => $notification));
  }


  public static function with_officer_fields() {
    return self::left_join('notification_officer', function($join){
                  $join->on('notification_id', '=', 'notifications.id');
                  $join->on('notification_officer.officer_id', '=', DB::raw(Auth::officer()->id));
                })
                ->select(array('*',
                               'notifications.id as id',
                               'notifications.created_at as created_at',
                               'notifications.updated_at as updated_at'));
  }


}