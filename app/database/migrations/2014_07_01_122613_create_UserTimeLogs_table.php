<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTimeLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('user_time_logs');
		Schema::create('user_time_logs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('task_role_bind_id')->unsigned();;
			$table->integer('user_id')->unsigned();;

			$table->integer('minutes')->nullable()->default(0);
			$table->timestamp('time')->nullable();

			$table->string('comment')->nullable();

			$table->timestamps();

			$table->foreign('task_role_bind_id')
				->references('id')->on('task_role_binds')
				->onDelete('cascade');

			$table->foreign('user_id')
				->references('id')->on('users')
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
		// Schema::drop('UserTimeLogs');
		// Schema::dropIfExists('user_time_logs');
		// Schema::dropIfExists('User_Time_Logs');
	}

}
