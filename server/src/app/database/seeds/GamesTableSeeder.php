<?php

// Composer: "fzaninotto/faker": "v1.3.0"

class GamesTableSeeder extends Seeder {

	public function run()
	{


		
      DB::table('games')->delete();

      DB::table('games')->insert(
      array(
        array('id'=>1, "uid" => "290sdk230230", 'address' => "127.0.0.1", "started"=> "2014-01-01 00:00:00", 'ended'=>'2014-01-01 00:00:00'),
        array('id'=>2, "uid" => "290sdk230230", 'address' => "127.0.0.1", "started"=> "2014-01-01 00:00:00", 'ended'=>'2014-01-01 00:00:00'),
        array('id'=>3, "uid" => "290sdk230230", 'address' => "127.0.0.1", "started"=> "2014-01-01 00:00:00", 'ended'=>'2014-01-01 00:00:00'),
        array('id'=>4, "uid" => "290sdk230230", 'address' => "127.0.0.1", "started"=> "2014-01-01 00:00:00", 'ended'=>'2014-01-01 00:00:00')
        )
    );

      
		
	}

}