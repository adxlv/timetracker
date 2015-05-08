<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddClientsV2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clients', function(Blueprint $table)
		{
			$table->integer('bbox_nr');
			$table->string('name');
			
			$table->string('legal_street');
			$table->string('legal_city');
			$table->string('legal_country');
			$table->string('legal_postal');

			$table->string('reg_nr');

			$table->string('bank');
			$table->string('bank_nr');
			$table->string('bank_code');

			$table->string('contact_name');
			$table->string('contact_details');


		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clients', function(Blueprint $table)
		{
			$table->dropColumn(array(
				'bbox_nr',
				'name',
				
				'legal_street',
				'legal_city',
				'legal_country',
				'legal_postal',

				'reg_nr',

				'bank',
				'bank_nr',
				'bank_code',

				'contact_name',
				'contact_details',
			));
		});
	}

}
