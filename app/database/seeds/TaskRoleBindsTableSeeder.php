<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class TaskRoleBindsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		// foreach(range(1, 10) as $index)
		// {
		// 	TaskRoleBind::create([

		// 	]);
		// }

		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 1,
			'hours'      => 1,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 2,
			'hours'      => 2,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 3,
			'hours'      => 3,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 4,
			'hours'      => 4,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 5,
			'hours'      => 5,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 1,
			'jobrole_id' => 6,
			'hours'      => 6,
			'taskdone'   => false,
		]);

		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 1,
			'hours'      => 1,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 2,
			'hours'      => 2,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 3,
			'hours'      => 3,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 4,
			'hours'      => 4,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 5,
			'hours'      => 5,
			'taskdone'   => false,
		]);
		TaskRoleBind::create([
			'task_id'    => 2,
			'jobrole_id' => 6,
			'hours'      => 6,
			'taskdone'   => true,
		]);

	}

}