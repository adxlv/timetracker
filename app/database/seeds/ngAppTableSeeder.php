<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ngAppTableSeeder extends Seeder {

	public function run()
	{
		$all = NgAppTable::get();
		foreach ($all as $one) {
			$one->delete();
		}

		NgAppTable::create([
			'app_name' 	=> 'LayouterGrid',
			'data' 	=> array(
				'values' => array(
					['texts' => [ '100g/г','1890/451kJ/кДж','0g/г','0g/г','0g/г','0g/г','0g/г','0g/г' ]],
					['texts' => [ '40g/г**','756/180kJ/кДж','0g/г','0g/г','0g/г','0g/г','0g/г','0g/г' ]],
				),
				'languages' => array(
					[
						'texts' => [ 'Uzturvērtība','Enerģētiskā vērtība ','Tauki','tostarp piesātinātās taukskābes','Ogļhidrāti ','tostarp cukuri','Olbaltumvielas','Sāls'],
						'lang' => 'LV',
						'active' => true
					],
					[
						'texts' => [ 'Nutrition values','Energy','Fat','of which saturates','Carbohydrate','of which sugars','Protein','Salt'],
						'lang' => 'EN',
						'active' => true
					],
					[ 'texts' => ['Nährwerte','Energie','Fett','davon gesättigte Fettsäuren','Kohlenhydrate','davon Zucker','Eiweiß','Salz']],
					[ 'texts' => ['Maistingumas','Energinė vertė','Riebalai','iš kurių sočiųjų riebalų rūgščių','Angliavandeniai','iš kurių cukrų','Baltymai','Druska']],
					[ 'texts' => ['Toiteväärtus','Energiasisaldus','Rasvad','millest küllastunud rasvhapped','Süsivesikud','millest suhkrud','Valgud','Sool']],
					[ 'texts' => ['Пищевая ценность','Энергетическая ценность','Жир','из которых кислоты жирные насыщенные','Углеводы','из которых сахара','Белки','Соль']],
					[ 'texts' => ['?','Energijska vrednost','Maščobe','od tega nasičene maščobe','Ogljikovi hidrati','od tega sladkorji','Beljakovine','Sol']],
					[ 'texts' => ['?','Energetická hodnota','Tuky','z toho nasycené mastné kyseliny','Sacharidy','z toho cukry','Bílkoviny','Sůl']],
					[ 'texts' => ['?','Énergie','Graisses','dont acides gras saturés','Glucides','dont sucres','Protéines','Sel']],
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