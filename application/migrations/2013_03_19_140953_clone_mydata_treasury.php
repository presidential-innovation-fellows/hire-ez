<?php

class Clone_Mydata_Treasury {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{

		$myDataProject = DB::table('projects')->where('title', '=', 'MyData Initiatives')->first();
		$myDataApplicants = DB::table('bids')->where('project_id', '=', $myDataProject->id)->get();

		$farmer = User::where('email', '=', 'John_P_Farmer@ostp.eop.gov')->first()->officer;
    $gallagher = User::where('email', '=', 'arianne_j_gallagher@ostp.eop.gov')->first()->officer;

    $newProjId = DB::table('projects')->insert_get_id(array(
   			'title' => 'MyData: Finance',
   			'agency' => 'Treasury',
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

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Project::where('title', '=', 'MyData: Finance')->delete();
	}

}