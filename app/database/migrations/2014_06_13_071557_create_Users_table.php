<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('users');
	    Schema::create('users', function($table)
	    {
	        $table->increments('id');
	        $table->string('email')->unique();
	        $table->string('login')->unique();
	        
	        $table->string('name');
	        $table->string('surname');
	        $table->string('pict')->default('/store/img/users/default.jpg');

	        $table->string('pass');

	        $table->string('disabled')->default(false);

	        $table->integer('group')->default(0);


	        $table->rememberToken();
	        $table->timestamps();
	        $table->softDeletes();
	    });
	} 


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('Users');
		// Schema::dropIfExists('users');
	}

}