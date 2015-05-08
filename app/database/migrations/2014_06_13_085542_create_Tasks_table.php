<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('tasks');
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('estimate_id')->unsigned()->nullable();
			$table->integer('project_id')->unsigned()->nullable();

			$table->string('title');
			$table->boolean('done')->default(false);

			$table->string('group')->nullable();;
			
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('estimate_id')
				->references('id')->on('estimates')
				->onDelete('cascade');
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
		// Schema::dropIfExists('Tasks');
		// Schema::dropIfExists('tasks');
	}

}
