<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::dropIfExists('expences');
		Schema::create('expences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('estimate_id')->unsigned();

			$table->string('title');
			$table->string('units')->default('gab');
			$table->integer('qty')->nullable();
			$table->float('price')->nullable();
			
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
		// Schema::dropIfExists('expences');
		// Schema::dropIfExists('Expences');
	}

}
