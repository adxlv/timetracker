<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPrepareForBills extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('estimates', function(Blueprint $table) {
		});

		Schema::rename('expences', 'estimate_expences');

		Schema::create('estimate_billentries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('estimate_id')->unsigned();

			$table->string('title');
			$table->float('summ');
			
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
		Schema::table('clients', function(Blueprint $table) {
		});

		Schema::rename('estimate_expences', 'expences');

		Schema::drop('estimate_billentries');
	}

}
