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

		 $newOpenDataProjects = array('Energy', 'Education', 'Smithsonian', 'NSF', 'Finance', 'Interior', 'Global Development', 'Transportation', 'Agriculture', 'Data.gov');
		 $newMyDataProjects = array('Blue Button', 'Green Button');

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

     }

	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		//TODO maybe?
	}

}