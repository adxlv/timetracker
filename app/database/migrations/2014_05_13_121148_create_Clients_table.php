<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('clients');
		Schema::create('clients', function(Blueprint $table)
		{
			$table->increments('id');
	        
	        $table->string('title');
	        $table->text('description')->nullable();
	        $table->text('web')->nullable();
	        $table->text('mail')->nullable();
	        $table->text('color')->nullable();
	        
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
		// Schema::dropIfExists('Clients');
		// Schema::dropIfExists('clients');
	}

}
