<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleUserBindsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('role_user_binds');
		Schema::create('role_user_binds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('taskrolebinds_id')->unsigned();;
			$table->integer('user_id')->unsigned();

			$table->integer('hours_done')->nullable();
			
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')->on('users')
				->onDelete('cascade');
				
			$table->foreign('taskrolebinds_id')
				->references('id')->on('task_role_binds')
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
		// Schema::dropIfExists('role_user_binds');
		// Schema::dropIfExists('Role_User_Binds');
	}

}
