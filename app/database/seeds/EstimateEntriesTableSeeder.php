<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class EstimateEntriesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	EstimateEntry::create([

		// 	]);
		// }
		
		EstimateEntry::create([
			'estimate_id' => 1,
			'title' => 'Web lapas programmēšanas darbi',
			'hours' => [1,2,3,4,5,6]
		]); 
		EstimateEntry::create([
			'estimate_id' => 1,
			'title' => 'Dizaina darbs',
			'hours' => [6,7,8,9,10,11]
		]); 
		
	}

}