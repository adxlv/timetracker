<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('projects');
	    Schema::create('projects', function($table)
	    {
	        $table->increments('id');
	        $table->integer('client_id')->unsigned();
	        
	        $table->string('title');
	        $table->text('description')->nullable();

	        $table->integer('created_by');
	        
	        $table->timestamps();
	        $table->softDeletes();

	        $table->foreign('client_id')
	        	->references('id')->on('clients')
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
		// Schema::dropIfExists('Projects');
		// Schema::dropIfExists('projects');
	}

}