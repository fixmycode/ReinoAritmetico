<?php

class ClientsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('clients')->delete();

    $clients = array(
        array('name' => 'Ruben Dario')
    );

    Client::insert($clients);
	}

}