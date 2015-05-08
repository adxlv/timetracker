<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ClientsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 10) as $index)
		{
			
		}

//Girts
// Client::create([ 'title' => 'Expo 2015']);
// Client::create([ 'title' => 'Galerija Rīga']);
// Client::create([ 'title' => 'Latvijas Balzāms']);
// Client::create([ 'title' => 'Lindenholma']);
// Client::create([ 'title' => 'Livna']);
// Client::create([ 'title' => 'RIB']);
// Client::create([ 'title' => 'Rietumu Banka']);
// Client::create([ 'title' => 'Runway']);
// Client::create([ 'title' => 'Vastint']);

// Signe
Client::create([ 'title' => 'Fazer']); //10
Client::create([ 'title' => 'Gutta']);
Client::create([ 'title' => 'Laima']);
Client::create([ 'title' => 'Marienbāde']);
Client::create([ 'title' => 'Selga']);
	}

}