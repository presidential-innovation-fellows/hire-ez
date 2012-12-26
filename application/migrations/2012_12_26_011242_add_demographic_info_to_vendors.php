<?php

class Add_Demographic_Info_To_Vendors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->string('demographic_survey_key')->nullable();
			$t->string('gender')->nullable();
			$t->string('race_1')->nullable();
			$t->string('race_2')->nullable();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function($t){
			$t->string('demographic_survey_key');
			$t->drop_column('gender');
			$t->drop_column('race_1');
			$t->drop_column('race_2');
		});
	}

}