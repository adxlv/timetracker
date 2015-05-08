<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskRoleBindsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('task_role_binds');
		Schema::create('task_role_binds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id')->unsigned();;
			$table->integer('jobrole_id')->unsigned();;

			$table->integer('hours')->nullable()->default(null);
			$table->boolean('taskdone')->nullable()->default(false);

			$table->timestamps();

			$table->foreign('task_id')
				->references('id')->on('tasks')
				->onDelete('cascade');
				
			$table->foreign('jobrole_id')
				->references('id')->on('job_roles')
				->onDelete('cascade');;
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('Task_Role_Binds');
		// Schema::dropIfExists('task_role_binds');
	}

}
