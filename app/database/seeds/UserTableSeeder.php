<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		User::create([
			'email' 	=> 'guntis@brandbox.com',
		    'login' 	=> 'guntis',
		    'name' 		=> 'Guntis',
		    'surname' 	=> 'Šulcs',
		    'pict' 		=> '/store/img/users/guntis.jpg',
		    'group' 	=> 1,
		    'pass' 		=> Hash::make('vissbumbas'),
		    'disabled' 	=> false,
		]);
		
		User::create([
			'email' 	=> 'uldis@brandbox.com',
		    'login' 	=> 'uldis',
		    'name' 		=> 'Uldis',
		    'surname' 	=> 'Trapencieris',
		    'pict' 		=> '/store/img/users/uldis.jpg',
		    'pass' 		=> Hash::make('boxbrand'),
		    'disabled' 	=> false,
		]);
		
		User::create([
			'email' 	=> 'andrejs@brandbox.com',
		    'login' 	=> 'andrejs',
		    'name' 		=> 'Andrejs',
		    'surname' 	=> 'Janaitis',
		    'pict' 		=> '/store/img/users/andrejs.jpg',
		    'pass' 		=> Hash::make('boxbrand'),
		    'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'girts@brandbox.com',
		    'login' 	=> 'girts',
		    'name' 		=> 'Ģirts',
		    'surname' 	=> 'Strumpmanis',
		    'pict' 		=> '/store/img/users/girts.jpg',
		    'pass' 		=> Hash::make('boxbrand'),
		    'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'signe@brandbox.com',
		    'login' 	=> 'signe',
		    'name' 		=> 'Signe',
		    'surname' 	=> 'Rāte',
		    'pict' 		=> '/store/img/users/signe.jpg',
		    'pass' 		=> Hash::make('boxbrand'),
		    'disabled' 	=> false,
		]);


		User::create([
			'email' 	=> 'maruta@brandbox.com',
			'login' 	=> 'maruta',
			'name' 		=> 'Maruta',
			'surname' 	=> 'Pūpola',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'linda@brandbox.com',
			'login' 	=> 'linda',
			'name' 		=> 'Linda',
			'surname' 	=> 'Jākobsone',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'ginta@brandbox.com',
			'login' 	=> 'ginta',
			'name' 		=> 'Ginta',
			'surname' 	=> 'Vecbaštika',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'misa@brandbox.com',
			'login' 	=> 'misa',
			'name' 		=> 'Michaela',
			'surname' 	=> 'Kováčová',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'andra@brandbox.com',
			'login' 	=> 'andra',
			'name' 		=> 'Andra',
			'surname' 	=> 'Treibaha',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'elga@brandbox.com',
			'login' 	=> 'elga',
			'name' 		=> 'Elga',
			'surname' 	=> 'Lakstiņa Lakstīgala',
			'pass' 		=> Hash::make('boxbrand'),
			'disabled' 	=> false,
		]);

		User::create([
			'email' 	=> 'guntis1@brandbox.com',
		    'login' 	=> 'test',
		    'name' 		=> 'Tests',
		    'surname' 	=> 'Testiņš',
		    'pict' 		=> '/store/img/users/1.jpg',
		    'pass' 		=> Hash::make('vissbumbas'),
		    'disabled' 	=> false,
		]);


		NgAppTable::create([
			'app_name' 	=> 'LayouterGrid',
			'data' 	=> array(
				'languages' => array(
					['texts' => ['Uzturvērtība','Enerģētiskā vērtība ','Tauki','tostarp piesātinātās taukskābes','Ogļhidrāti ','tostarp cukuri','Olbaltumvielas','Sāls']],
					['texts' => ['Nutrition values','Energy','Fat','of which saturates','Carbohydrate','of which sugars','Protein','Salt']],
				)
			),
		]);

		// foreach(range(1, 19) as $index)
		// {
		// 	User::create([
		// 	    'email' 	=> $faker->email,
		// 	    'login' 	=> $faker->firstName,
		// 	    'name' 		=> $faker->firstName,
		// 	    'surname' 	=> $faker->lastName,
		// 	    'pict' 		=> 'http://api.randomuser.me/portraits/men/'.$faker->randomDigitNotNull.'.jpg',
		// 	    'pass' 		=> $faker->word,
		// 	    'disabled' 	=> false,
		// 	]);
		// }
	}

}