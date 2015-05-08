<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class InstallNgAppsTable extends Migration {

	public function up()
	{
		// Schema::dropIfExists('ng_app_table');
		// Schema::create('ng_app_tables', function(Blueprint $table)
		// {
		// 	$table->increments('id');

		// 	$table->string('app_name')->unique();
		// 	$table->text('data')->nullable();

		// 	$table->timestamps();
		// 	$table->softDeletes();
		// });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::dropIfExists('ng_app_table');
		// Schema::dropIfExists('ng_app_tables');
	}

}