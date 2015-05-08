<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ProjectsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			
		}
		/*
		$the_id = 4; //girts

		Project::create([
			'client_id' => '1',
			'created_by' => $the_id,
			'title' => 'Grafiskā Identitāte',
		]);Project::create([
			'client_id' => '1',
			'created_by' => $the_id,
			'title' => 'Stends',
		]);

		
		Project::create([
			'client_id' => '2',
			'created_by' => $the_id,
			'title' => 'Buklets',
		]);Project::create([
			'client_id' => '2',
			'created_by' => $the_id,
			'title' => 'Dāvanu Karte',
		]);

		Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'Auto Furgons',
		]);Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'BBC Kokteiļi',
		]);Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'Queen vodka',
		]);Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'RBB logo stila grāmata',
		]);Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'RBB super VIP dāvanu kaste',
		]);Project::create([
			'client_id' => '3',
			'created_by' => $the_id,
			'title' => 'Veikalu Stila grāmata',
		]);

		Project::create([
			'client_id' => '4',
			'created_by' => $the_id,
			'title' => 'Web',
		]);

		Project::create([
			'client_id' => '5',
			'created_by' => $the_id,
			'title' => 'Krāsas iepakojums',
		]);

		Project::create([
			'client_id' => '6',
			'created_by' => $the_id,
			'title' => 'bukletu stends',
		]);

		Project::create([
			'client_id' => '7',
			'created_by' => $the_id,
			'title' => 'eCom',
		]);

		Project::create([
			'client_id' => '8',
			'created_by' => $the_id,
			'title' => 'Grafiskā Identitāte',
		]);

		Project::create([
			'client_id' => '9',
			'created_by' => $the_id,
			'title' => 'Futuris',
		]);

		Project::create([
			'client_id' => '9',
			'created_by' => $the_id,
			'title' => 'Zaļā 1',
		]);
		*/
	
		$the_id = 5; //girts
	
		Project::create([
			'client_id' => '29',
			'created_by' => $the_id,
			'title' => 'Druvas maizes',
		]);Project::create([
			'client_id' => '29',
			'created_by' => $the_id,
			'title' => 'Druvas reklāma',
		]);

		Project::create([
			'client_id' => '30',
			'created_by' => $the_id,
			'title' => 'Good Energy',
		]);

		Project::create([
			'client_id' => '30',
			'created_by' => $the_id,
			'title' => 'Sulas Nektāra Dzērieni',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Exporta : Asorti 215g USA uzlikas Meksika & Puertoriko',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Exporta : sienas kalendāra augša',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Exporta Asorti',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Hrum glazētie vafeļu batoni (2 garšas)',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Kaļiņingrad Prozit 180g',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Konfekšu Maisi (6 veidi)',
		]);

		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Lielkonfektes',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'poraino tāfeļu redizains',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Rīga Prozit 180g',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Selga. Konfekte un Maiss',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Tāfeles',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Tallinn. Konfekte un Maiss ',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'transportkārbas un maisi',
		]);
		
		Project::create([
			'client_id' => '31',
			'created_by' => $the_id,
			'title' => 'Vafeļtortes',
		]);
		
		Project::create([
			'client_id' => '32',
			'created_by' => $the_id,
			'title' => 'Produktu etiķetes',
		]);

		Project::create([
			'client_id' => '33',
			'created_by' => $the_id,
			'title' => 'Cepumi ar riekstiem 180g',
		]);
	}

}