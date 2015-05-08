<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('branches', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('title');
			$table->string('description')->nullable();
			$table->string('type')->nullable();
			$table->string('chairman')->nullable();
			$table->string('image_url')->nullable();
			

			$table->string('name')->nullable();
			
			$table->string('legal_street')->nullable();
			$table->string('legal_city')->nullable();
			$table->string('legal_country')->nullable();
			$table->string('legal_postal')->nullable();

			$table->string('reg_nr')->nullable();

			$table->string('bank')->nullable();
			$table->string('bank_nr')->nullable();
			$table->string('bank_code')->nullable();

			$table->string('contact_name')->nullable();
			$table->string('contact_details')->nullable();

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
		Schema::drop('branches');
	}

}
