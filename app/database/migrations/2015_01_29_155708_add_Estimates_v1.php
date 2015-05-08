<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddEstimatesV1 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('estimates', function(Blueprint $table)
		{
			
			$table->string('bill_nr')->nullable();
			$table->timestamp('bill_created_at')->nullable();
			$table->integer('bill_created_by')->unsigned()->nullable();

			$table->foreign('bill_created_by')
	        	->references('id')->on('users');

	        
			$table->float('total_summ')->nullable();

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('estimates', function(Blueprint $table)
		{
			$table->dropColumn(array(
				'bill_nr',
				'bill_created_by',
				'bill_created_by',
				'total_summ'
			));
		});
	}

}
