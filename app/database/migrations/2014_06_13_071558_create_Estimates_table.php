<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstimatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('estimates');	
		Schema::create('estimates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('project_id')->unsigned();
			$table->integer('bound_to_estimate_id')->unsigned()->nullable();

			$table->string('title');
			$table->text('description')->nullable();
			$table->string('involved_roles');
			$table->float('discount');

			$table->string('group');

			$table->timestamps();
			$table->softDeletes();

			$table->foreign('project_id')
				->references('id')->on('projects')
				->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('Estimates');
		// Schema::dropIfExists('estimates');
	}

}
