<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class EstimatesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	Estimate::create([

		// 	]);
		// }
		
		Estimate::create([
			'project_id' => 1,
			'title' => 'Make Web App',
			'involved_roles' => array(
				'id'=>[1,2,3,4,5,6],
				'salary'=>[50,90,50,70,45,45]
			),
			'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, dolore, error, repudiandae.',
			'discount' => 1,
			// 'group' => array("value"=>0,"name"=>"BrandBox HQ"),
			'group' => 0,
		]);
		// Estimate::create([
		// 	'project_id' => 1,
		// 	'title' => 'Izveidot dizainu',
		// 	'involved_roles' => array(
		// 		'id'=>[1,2,3,4,5,6],
		// 		'salary'=>[50,80,50,70,45,45]
		// 	),
		// 	'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, dolore, error, repudiandae.',
		// 	'discount' => 1,
		// 	// 'group' => array("value"=>1,"name"=>"BrandBox Digital"),
		// 	'group' => 1,

		// ]);
		
	}

}