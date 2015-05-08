<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('job_roles');
		Schema::create('job_roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('img_url');

			$table->float('salary_neto');
			$table->float('salary_bruto');
			
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('Job_Roles');
		// Schema::dropIfExists('job_roles');
	}

}
