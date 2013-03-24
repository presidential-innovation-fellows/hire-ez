<?php

class NotificationOfficer extends Eloquent {

  public static $timestamps = true;

  public static $table = "notification_officer";

  public function notification() {
    return $this->belongs_to('Notification');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public static function mine_for_notification($notification_id) {
    if (!Auth::officer()) return false;

    $notification_officer = self::where_notification_id($notification_id)
                    ->where_officer_id(Auth::officer()->id)
                    ->first();

    if (!$notification_officer) {
      $notification_officer = self::create(array('notification_id' => $notification_id, 'officer_id' => Auth::officer()->id));
    }

    return $notification_officer;
  }

}
