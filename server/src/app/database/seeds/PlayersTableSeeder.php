<?php

class PlayersTableSeeder extends Seeder {

	public function run()
	{
    DB::table('players')->delete();
    DB::table('character_type')->delete();

    DB::table('character_type')->insert(array('id'=>'1', 'name'=>'Mago', 'sex'=>'M'));

    $classroom = Classroom::whereName('3ro Basico')->first();
    $client = Client::where('name','=','Ruben Dario')->first();
    DB::table('players')->insert(
      array(
        array("name" => "Jon Snow", 'android_id' => str_random(64), 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
        array("name" => "Jon Snow", 'android_id' => str_random(64), 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
        array("name" => "Jon Snow", 'android_id' => str_random(64), 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
        array("name" => "Jon Snow", 'android_id' => str_random(64), 'character_type_id'=>1, 'classroom_id'=>$classroom->id,'client_id'=>$client->id),
        )
    );


	}

}