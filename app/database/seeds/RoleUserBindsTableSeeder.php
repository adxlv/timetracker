<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class RoleUserBindsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	RoleUserBind::create([

		// 	]);
		// }
		RoleUserBind::create([
			'taskrolebinds_id'=>6,
			'user_id'=>1,
			'hours_done'=>2
		]);
	}

}