<?php

class ClassroomsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('classrooms')->delete();

    $classrooms = array(
        new Classroom(['name' => '1ro Basico']),
        new Classroom(['name' => '2ro Basico']),
        new Classroom(['name' => '3ro Basico']),
        new Classroom(['name' => '4ro Basico']),
        new Classroom(['name' => '5ro Basico'])
    );

    Client::whereName('Ruben Dario')->first()->classrooms()->saveMany($classrooms);
	}

}