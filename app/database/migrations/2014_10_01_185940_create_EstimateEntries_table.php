<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstimateEntriesTable extends Migration {

	public function up()
	{
		// Schema::dropIfExists('estimate_entries');
		Schema::create('estimate_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('estimate_id')->unsigned();

			$table->string('title');
			$table->string('group')->default('none');
			$table->string('hours')->nullable();

			$table->integer('sortorder')->default(0);
			$table->boolean('is_header')->default(false);
			
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('estimate_id')
				->references('id')->on('estimates')
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
		// Schema::dropIfExists('estimate_entries');
	}

}
