<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class TasksTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	Task::create([

		// 	]);
		// }

		Task::create([
			'project_id' => 1,
			'title' => 'WEB',
		]);
		Task::create([
			'project_id' => 1,
			'title' => 'Dizaina darbs',
		]);

	}

}