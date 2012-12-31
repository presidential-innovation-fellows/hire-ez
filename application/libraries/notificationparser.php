<?php

Class NotificationParser {

  public static function parse($notification) {
    $return_array = array();

    if ($notification->notification_type == "ApplicantHired") {
      $bid = $notification->payload["bid"];
      $return_array["subject"] = e($bid["vendor"]["name"]." has been hired for ".$bid["project"]["title"]."!");
      $return_array["line1"] = e($bid["vendor"]["name"])." has been hired for ".
                               HTML::link(route('bid', array($bid["project_id"], $bid["id"])), e($bid["project"]["title"]))."!";
      $return_array["link"] = route('bid', array($bid["project_id"], $bid["id"]));

    } elseif ($notification->notification_type == "ApplicantComment") {
      $comment = $notification->payload["comment"];
      $officer = $notification->payload["officer"];
      $vendor = $notification->payload["vendor"];
      $return_array["subject"] = e(($officer["name"] ?: $officer["user"]["email"])." has posted a comment about ".$vendor["name"].".");
      $return_array["line1"] = (e($officer["name"]) ?: e($officer["user"]["email"]))." has posted a comment about ".HTML::link(route('vendor', $vendor["id"]), e($vendor["name"])).".";
      $return_array["link"] = route('vendor', $vendor["id"]);

    } elseif ($notification->notification_type == "ProjectComment") {
      $comment = $notification->payload["comment"];
      $officer = $notification->payload["officer"];
      $project = $notification->payload["project"];
      $return_array["subject"] = e(($officer["name"] ?: $officer["user"]["email"])." has posted a comment about ".$project["title"].".");
      $return_array["line1"] = (e($officer["name"]) ?: e($officer["user"]["email"]))." has posted a comment about ".HTML::link(route('comments', $project["id"]), e($project["title"])).".";
      $return_array["link"] = route('comments', $project["id"]);

    } elseif ($notification->notification_type == "ApplicantForwarded") {
      $bid = $notification->payload["bid"];
      $project = $notification->payload["project"];
      $from_project = $notification->payload["from_project"];
      $return_array["subject"] = e($from_project["title"]." has forwarded an applicant to ".$project["title"].".");
      $return_array["line1"] = e($from_project["title"])." has forwarded an applicant to ".HTML::link(route('project', $project["id"]), e($project["title"])).".";
      $return_array["link"] = route('bid', array($project["id"], $bid["id"]));

    } elseif ($notification->notification_type == "CollaboratorAdded") {
      $officer = $notification->payload["officer"];
      $project = $notification->payload["project"];
      $return_array["subject"] = e(($officer["name"] ?: $officer["user"]["email"])." has been added as a collaborator on ".$project["title"].".");
      $return_array["line1"] = e(($officer["name"] ?: $officer["user"]["email"]))." has been added as a collaborator on ".HTML::link(route('project_admin', $project["id"]), e($project["title"])).".";
      $return_array["link"] = route('project_admin', $project["id"]);
    }

    $return_array["timestamp"] = date('c', is_object($notification->created_at) ? $notification->created_at->getTimestamp() : strtotime($notification->created_at));
    return $return_array;
  }

}