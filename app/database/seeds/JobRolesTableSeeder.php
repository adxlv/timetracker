<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class JobRolesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	JobRole::create([

		// 	]);
		// }
		JobRole::create([
			'title' => 'Projektu vadītājs',
			'img_url' => '/img/jobroles/pm.png',
			'salary_bruto' => 25.00,
			'salary_neto' => 50.00,
		]);
		JobRole::create([
			'title' => 'Radošais direktors',
			'img_url' => '/img/jobroles/pm.png',
			'salary_bruto' => 35.00,
			'salary_neto' => 90.00,
		]);
		JobRole::create([
			'title' => 'Tekstu autors',
			'img_url' => '/img/jobroles/artist.png',
			'salary_bruto' => 25.00,
			'salary_neto' => 50.00,
		]);
		JobRole::create([
			'title' => 'Dizaineris',
			'img_url' => '/img/jobroles/artist.png',
			'salary_bruto' => 35.00,
			'salary_neto' => 70.00,
		]);
		JobRole::create([
			'title' => 'Maketētājs',
			'img_url' => '/img/jobroles/dev.png',
			'salary_bruto' => 22.50,
			'salary_neto' => 45.00,
		]);
		JobRole::create([
			'title' => 'Programmētājs',
			'img_url' => '/img/jobroles/dev.png',
			'salary_bruto' => 22.50,
			'salary_neto' => 45.00,
		]);
	}

}