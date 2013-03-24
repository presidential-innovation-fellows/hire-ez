<?php

class Increment_Deploy_Timestamp_Task extends Task {

	public function run() {
    file_put_contents("deploy_timestamp.txt", time());
    print "Incremented deploy timestamp.";
  }

}
