<?php

class Remove_Submitted_At_From_Bids {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bids', function($t){
			$t->drop_column('submitted_at');
			$t->drop_column('deleted_at');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bids', function($t){
			$t->date('submitted_at')->nullable();
			$t->date('deleted_at')->nullable();
		});
	}

}