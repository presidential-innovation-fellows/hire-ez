<?php

class Split_Multiple_Projects {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		 $openDataProject = DB::table('projects')->where('title', '=', 'Open Data Initiatives')->first();
		 $openDataApplicants = DB::table('bids')->where('project_id', '=', $openDataProject->id)->get();

		 $myDataProject = DB::table('projects')->where('title', '=', 'MyData Initiatives')->first();
		 $myDataApplicants = DB::table('bids')->where('project_id', '=', $myDataProject->id)->get();

           $myUSAProject = DB::table('projects')->where('title', '=', 'MyUSA')->first();
           $myUSAApplicants = DB::table('bids')->where('project_id', '=', $myUSAProject->id)->get();

		 $newOpenDataProjects = array('Energy', 'Interior', 'Education', 'Smithsonian', 'NSF', 'Treasury', 'FDA', 'Global Development', 'USDA', 'Data.gov');
		 $newMyDataProjects = array('Blue Button', 'Green Button');
           $newMyUSAProjects = array('BusinessUSA');

           $farmer = User::where('email', '=', 'John_P_Farmer@ostp.eop.gov')->first()->officer;
           $gallagher = User::where('email', '=', 'arianne_j_gallagher@ostp.eop.gov')->first()->officer;

     foreach ($newOpenDataProjects as $p) {
     	$newProjId = DB::table('projects')->insert_get_id(array(
     			'title' => 'Open Data: ' . $p,
     			'agency' => $p,
     			'body' => $openDataProject->body,
     			'tagline' => $openDataProject->tagline,
     			'created_at' => $openDataProject->created_at,
     			'updated_at' => $openDataProject->updated_at
     		));

     	foreach($openDataApplicants as $a) {
     		$cloneBid = get_object_vars($a);
     		$cloneBid['id'] = null;
     		$cloneBid['project_id'] = $newProjId;
     		DB::table('bids')->insert($cloneBid);
     	}

          DB::table('project_collaborators')->insert(array(
               array(
               'officer_id' => $farmer->id,
               'project_id' => $newProjId,
               'owner' => true
               ),
               array(
               'officer_id' => $gallagher->id,
               'project_id' => $newProjId,
               'owner' => false
               ),
          ));
     }

     foreach ($newMyDataProjects as $p) {
     	$newProjId = DB::table('projects')->insert_get_id(array(
     			'title' => 'MyData: ' . $p,
     			'agency' => $p,
     			'body' => $myDataProject->body,
     			'tagline' => $myDataProject->tagline,
     			'created_at' => $myDataProject->created_at,
     			'updated_at' => $myDataProject->updated_at
     		));

     	foreach($myDataApplicants as $a) {
     		$cloneBid = get_object_vars($a);
     		$cloneBid['id'] = null;
     		$cloneBid['project_id'] = $newProjId;
     		DB::table('bids')->insert($cloneBid);
     	}

          DB::table('project_collaborators')->insert(array(
               'officer_id' => $farmer->id,
               'project_id' => $newProjId,
               'owner' => true
          ));

     }

     foreach ($newMyUSAProjects as $p) {
          $newProjId = DB::table('projects')->insert_get_id(array(
                    'title' => 'MyUSA: ' . $p,
                    'agency' => $p,
                    'body' => $myUSAProject->body,
                    'tagline' => $myUSAProject->tagline,
                    'created_at' => $myUSAProject->created_at,
                    'updated_at' => $myUSAProject->updated_at
               ));

          foreach($myUSAApplicants as $a) {
               $cloneBid = get_object_vars($a);
               $cloneBid['id'] = null;
               $cloneBid['project_id'] = $newProjId;
               DB::table('bids')->insert($cloneBid);
          }

          DB::table('project_collaborators')->insert(array(
               'officer_id' => $farmer->id,
               'project_id' => $newProjId,
               'owner' => true
          ));
     }

	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{

          $openDataProjects = Project::where('title', 'LIKE', 'Open Data: %')->delete();
          $myDataProjects = Project::where('title', 'LIKE', 'MyData: %')->delete();
          $myUSAProjects = Project::where('title', 'LIKE', 'MyUSA: %')->delete();

	}

}