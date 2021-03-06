<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProjectsV1 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function(Blueprint $table)
		{
			$table->timestamp('archived_at');
			$table->integer('archived_by')->unsigned()->nullable();

			$table->foreign('archived_by')
	        	->references('id')->on('users');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects', function(Blueprint $table)
		{
			$table->dropColumn(array(
				'archived_at',
				'archived_by'
			));
		});
	}

}
