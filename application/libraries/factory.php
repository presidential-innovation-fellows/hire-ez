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
                          'resume' => "<h2>Rajan Whitevan</h2><div>2008 West 7th Place ~ San Diego, CA 98876</div><div>912-767-0087 ~ rwhitevan@msn.com</div><div><br></div><h3>Qualifications</h3><div>Solutions-focused, team oriented Senior Technical Support Analyst with broad-based experience and hands-on skill in the successful implementation of highly effective helpdesk operations and the cost-effective management of innovative customer and technical support strategies. Proven ability to successfully analyze an organization's critical support requirements, identify deficiencies and potential opportunities, and develop innovative solutions for increasing reliability and improving productivity. A broad understanding of computer hardware and software, including installation, configuration, management, troubleshooting, and support.</div><div><br></div><h3>Technical Skills</h3><div>Linux/Unix · Windows 9x/NT/2000/XP · Oracle · FoxPro · DBase II</div><div>C · C · BASIC · MS Office · MS-Money · Encarta</div><div><br></div><h3>Professional Experience</h3><div>Technical Support Enterprises -Wichita, Kansas</div><div>2002 - Present</div><div><br></div><div>Mentor/ Escalation Support (8/2003- - present)</div><div>· Promoted to Mentor, handling escalation processes and mentoring other support professionals while working via phone, email, and chat.</div><div>Technical Support Manager for Microsoft Money account (6/2003 - 7/2003)</div><div>· Promoted from frontline support professional to second-tier technical support manager, supervising frontline phone support for Microsoft Corporation for Money, Encarta, PC Games, and other similar products.</div><div>· Responsible for the strategic development and implementation of cost-effective training and support solutions that are designed to provide improved productivity, streamlined operations, and faster access to critical information.</div><div>· Implement effective customer satisfaction strategies by identifying and eliminating the root causes of customer problems.</div><div>· Utilize NICE Application and AVAYA program to manage call center metrics, lead call calibrations, and perform random-sample audits on email and chat sessions.</div><div>Quality Monitoring Lead (12/2002 - 5/2003)</div><div>· Participate in quality assurance procedures, verifying sales calls taken by the other agents, provide constructive feedback to agents, and adhere to the specific support levels that have been purchased by the client.</div><div>Support Professional for Chase Bank (9/2002 - 12/2002)</div><div>· Provide comprehensive system support, configuration, maintenance, and training for Providian Bank and promoted value added products and services for existing clients.</div><div><br></div><div>Micron Computers Ltd.-India</div><div>1998-2002</div><div>Hardware Engineer</div><div>· Performed hardware and software installations and provided high-level customer care, training, and technical support.</div><div>· Assembled and installed a wide array of computer systems, workstations, and peripheral hardware.</div><div><br></div><h3>Education</h3><div>Diploma in Computer Science</div><div>Independent Colleges Online - 2001</div>"));

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
                       'body' => $faker->paragraph(25)));


    $b->starred = rand(0,1);
    $b->vendor_id = $vendor->id;

    // Dismiss 1/3 of the bids
    if (rand(0,2) === 0) {
      $b->dismissed_at = new \DateTime;
    }

    $b->save();
  }

}