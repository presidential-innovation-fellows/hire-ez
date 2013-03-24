<?php

class Add_Links_To_Vendors {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function($t){
			$t->string('link_1')->nullable();
			$t->string('link_2')->nullable();
			$t->string('link_3')->nullable();
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
			$t->drop_column('link_1');
			$t->drop_column('link_2');
			$t->drop_column('link_3');
		});
	}

}