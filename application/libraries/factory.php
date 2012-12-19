<?php

Class Factory {

  public static $vendor_count = 1;

  public static $officer_count = 1;

  public static $agencies = array("Department of Justice", "Health and Human Services", "Small Business Administration",
                                  "General Services Administration", "Department of Education");

  public static $offices = array("Office of Capital Access", "Office of Credit Risk Management", "Office of Investment",
                                 "Office of Field Operations", "Office of Surety Guarantees", "Office of Hearings & Appeals");

  public static $project_titles = array("Smithsonian Dinosaurs meet HTML5", "OurGov", "Participation Reduction Act", "Periwinkle Button", "Semi-ajar Data", "Even Better than Mobile Money");

  public static function vendor() {
    $faker = Faker\Factory::create();

    $image_urls = array('http://i.imgur.com/Gh4ZX.png', 'http://i.imgur.com/vySFV.png', 'http://i.imgur.com/RdBae.png',
                        'http://i.imgur.com/ED5fa.png', 'http://i.imgur.com/gJncN.png', 'http://i.imgur.com/3pKFS.png',
                        'http://i.imgur.com/3pKFS.png');

    $u = User::create(array('email' => 'vendor'.self::$vendor_count.'@example.com',
                            'password' => 'password'));

    $v = new Vendor(array('name' => $faker->name,
                          'email' => 'vendor'.self::$vendor_count.'@example.com',
                          'phone' => '312-555-1212',
                          'zip' => '60610',
                          'resume' => 'I have a <i>lot</i> of experience.'));

    $v->save();

    self::$vendor_count++;

  }

  public static function officer() {
    $faker = Faker\Factory::create();



    $u = User::create(array('email' => 'officer'.self::$officer_count.'@example.gov',
                            'password' => 'password'));

    $o = Officer::create(array('user_id' => $u->id,
                               'phone' => $faker->phoneNumber,
                               'name' => $faker->firstName . " " . $faker->lastName,
                               'title' => (rand(1,2) == 1) ? "Contracting Officer" : "Program Officer",
                               'agency' => self::$agencies[array_rand(self::$agencies)]));

    $o->role = Officer::ROLE_APPROVED;
    $o->save();

    self::$officer_count++;

    return $o;
  }

  public static function project($fork_from_project_id) {
    $faker = Faker\Factory::create();

    $original_project = Project::find($fork_from_project_id);

    $due_at = new \DateTime();
    $due_at->setTimestamp(rand(1356998400, 1364792400));

    $p = new Project(array('title' => array_pop(self::$project_titles),
                           'agency' => self::$agencies[array_rand(self::$agencies)],
                           'office' => self::$offices[array_rand(self::$offices)],
                           'body' => $faker->paragraph,
                           'proposals_due_at' => $due_at
                           ));

    $p->save();

    $p->officers()->attach(Officer::first()->id, array('owner' => true));
    return $p;
  }

  public static function bids($vendor) {
    for ($i = 0; $i < rand(1, 5); $i++) Factory::bid($vendor);
  }

  public static function bid($vendor) {
    $faker = Faker\Factory::create();

    $p = Project::order_by(\DB::raw('RAND()'))->first();

    while (in_array($p->id ,$vendor->bids()->lists('id'))) {
      $p = Project::order_by(\DB::raw('RAND()'))->first();
    }


    $b = new Bid(array('project_id' => $p->id,
                       'body' => $faker->paragraph));


    $b->starred = rand(0,1);
    $b->vendor_id = $vendor->id;
    $b->save();

    if (rand(0,6) === 0) {
      $b->delete_by_vendor();
    } else {
      if (rand(0,1) === 0) {
        $submitted_at = new \DateTime;
        $b->submitted_at = (rand(0,6) === 0) ? $submitted_at : null;
        $b->submit();

        // Dismiss 1/3 of the bids
        if (rand(0,2) === 0) {
          $b->dismiss();
          // Un-dismiss 1/2 of these
          if (rand(0,1) === 0) $b->undismiss();
        }
      }
    }

  }

}