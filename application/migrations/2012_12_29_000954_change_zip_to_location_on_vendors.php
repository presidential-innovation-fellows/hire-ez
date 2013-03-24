<?php

class Change_Zip_To_Location_On_Vendors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->drop_column('zip');
			$t->string('location');
			$t->decimal('latitude', 14, 10)->nullable();
			$t->decimal('longitude', 14, 10)->nullable();
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
			$t->string('zip');
			$t->drop_column('location');
			$t->drop_column('latitude');
			$t->drop_column('longitude');
		});
	}

}