<?php

class PlayersTableSeeder extends Seeder {

	public function run()
	{
        DB::table('players')->delete();
        DB::table('character_type')->delete();

        DB::table('character_type')->insert(array('uid' => 0, 'name'=>'Warrior', 'sex'=>'M'));
        DB::table('character_type')->insert(array('uid' => 1, 'name'=>'Wizard',  'sex'=>'M'));
        DB::table('character_type')->insert(array('uid' => 2, 'name'=>'Archer',  'sex'=>'M'));

        $classroom = Classroom::whereName('3ro Basico')->first();
        $client = Client::where('name','=','Ruben Dario')->first();
        DB::table('players')->insert(
          array(
            array("name" => "Jon Snow",   'android_id' => 'know',   'character_type_id' => 1, 'classroom_id' => $classroom->id, 'client_id' => $client->id, 'credits' => 50),
            array("name" => "Arya Stark", 'android_id' => 'niddle', 'character_type_id' => 1, 'classroom_id' => $classroom->id, 'client_id' => $client->id, 'credits' => 50),
            )
        );
	}

}