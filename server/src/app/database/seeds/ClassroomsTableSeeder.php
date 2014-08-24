<?php

class ClassroomsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('classrooms')->delete();

    $classrooms = array(
        new Classroom(['name' => '1ro Básico']),
        new Classroom(['name' => '2do Básico']),
        new Classroom(['name' => '3ro Básico']),
        new Classroom(['name' => '4to Básico']),
        new Classroom(['name' => '5to Básico'])
    );

    Client::whereName('Ruben Dario')->first()->classrooms()->saveMany($classrooms);
	}

}