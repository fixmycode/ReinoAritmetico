<?php

// Composer: "fzaninotto/faker": "v1.3.0"

class GamesTableSeeder extends Seeder {

	public function run()
	{
     DB::table('games')->delete();

     DB::table('games')->insert(
            array(
                        'uid' => 'sdf',
                        'address' => 'localhost:3000',
                        'started' => new DateTime
            )
        );
	}

}