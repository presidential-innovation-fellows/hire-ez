<?php

class Initial_Schema {

  /**
   * Make changes to the database.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function($t){
      $t->increments('id');
      $t->string('email');

      // Taking a lot of inspiration from the Devise Rails gem.
      // This stuff should be pretty easy to implement, and is
      // hopefully a good enough security practice for starters,
      // as opposed to tracking each login and each password reset request.
      //
      // When the officer signs up, encrypted_password is null.
      // She is then sent an email with a link to confirm her account,
      // which then lets her create a password. The page is actually
      // the "reset password" page, but since we know that she's never
      // had a password (the sign_in_count is 0), we can make it appear
      // differently.
      $t->string('encrypted_password')->nullable();
      $t->string('reset_password_token')->nullable();
      $t->date('reset_password_sent_at')->nullable();
      $t->integer('sign_in_count');
      $t->date('current_sign_in_at')->nullable();
      $t->date('last_sign_in_at')->nullable();
      $t->string('current_sign_in_ip')->nullable();
      $t->string('last_sign_in_ip')->nullable();

      $t->boolean('send_emails')->default(true);

      $t->string('new_email');
      $t->string('new_email_confirm_token');

      $t->date('banned_at')->nullable();

      $t->integer('invited_by')->nullable()->unsigned();

      $t->timestamps();
    });

    Schema::create('bids', function($t){
      $t->increments('id');
      $t->integer('vendor_id')->unsigned();
      $t->integer('project_id')->unsigned();

      // @placeholder
      $t->text('body');

      $t->date('dismissed_at')->nullable();

      $t->boolean('starred');

      $t->date('submitted_at')->nullable();
      $t->date('deleted_at')->nullable();
      $t->date('awarded_at')->nullable();
      $t->integer('awarded_by')->nullable()->unsigned();

      $t->integer('total_stars');
      $t->integer('total_comments');

      $t->timestamps();
    });


    Schema::create('projects', function($t){
      $t->increments('id');
      $t->string('title');
      $t->string('agency');
      $t->string('office');

      // @placeholder
      $t->text('body');

      $t->date('proposals_due_at');

      $t->timestamps();
    });

    Schema::create('vendors', function($t){
      $t->increments('id');
      $t->integer('user_id')->nullable()->unsigned();
      $t->string('name');
      $t->string('email');
      $t->string('phone');
      // $t->string('address');
      // $t->string('city');
      // $t->string('state');
      $t->string('zip');
      $t->text('resume');

      $t->timestamps();
    });

    Schema::create('officers', function($t){
      $t->increments('id');
      $t->integer('user_id')->unsigned();
      $t->string('phone');
      $t->string('name');
      $t->string('title');
      $t->string('agency');

      $t->integer('role')->default(0);

      $t->timestamps();
    });

    Schema::create('notifications', function($t){
      $t->increments('id');
      $t->integer('target_id')->unsigned();
      $t->integer('actor_id')->nullable()->unsigned();
      $t->string('notification_type');
      $t->text('payload');
      $t->boolean('read');
      $t->integer('payload_id')->nullable();
      $t->string('payload_type')->nullable();
      $t->timestamps();
    });

    Schema::create('project_collaborators', function($t){
      $t->increments('id');
      $t->integer('officer_id')->unsigned();
      $t->integer('project_id')->unsigned();
      $t->boolean('owner');
      $t->timestamps();
    });

    Schema::create('bid_officer', function($t){
      $t->increments('id');
      $t->integer('officer_id')->unsigned();
      $t->integer('bid_id')->unsigned();
      $t->boolean('read');
      $t->boolean('starred');
      $t->timestamps();
    });

    ////////////// FOREIGN KEYS //////////////////


    Schema::table('project_collaborators', function($t){
      $t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
    });

    Schema::table('bid_officer', function($t){
      $t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
      $t->foreign('bid_id')->references('id')->on('bids')->on_delete('CASCADE');
    });

    Schema::table('notifications', function($t){
      $t->foreign('target_id')->references('id')->on('users')->on_delete('CASCADE');
      $t->foreign('actor_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('vendors', function($t){
      $t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('officers', function($t){
      $t->foreign('user_id')->references('id')->on('users')->on_delete('CASCADE');
    });

    Schema::table('bids', function($t){
      $t->foreign('vendor_id')->references('id')->on('vendors')->on_delete('CASCADE');
      $t->foreign('awarded_by')->references('id')->on('officers')->on_delete('SET NULL');
      $t->foreign('project_id')->references('id')->on('projects')->on_delete('CASCADE');
    });

    Schema::create('comments', function($t){
      $t->increments('id');
      $t->string('commentable_type');
      $t->integer('commentable_id');
      $t->integer('officer_id')->nullable()->unsigned();
      $t->text('body');
      $t->date('deleted_at')->nullable();
      $t->timestamps();
    });

    Schema::table('comments', function($t){
      $t->foreign('officer_id')->references('id')->on('officers')->on_delete('CASCADE');
    });

    Schema::create('sessions', function($t){
      $t->string('id');
      $t->integer('last_activity');
      $t->text('data');
    });

    Schema::table('users', function($t){
      $t->foreign('invited_by')->references('id')->on('users')->on_delete('SET NULL');
    });

  }

  /**
   * Revert the changes to the database.
   *
   * @return void
   */
  public function down()
  {
    throw new Exception("Can't migrate down. Please drop your database and migrate up.");
  }

}