<?php

class StudentsTableSeeder extends Seeder {

	public function run()
	{
    DB::table('players')->delete();

    $players = [
      new Student(["name" => "Jon Snow", 'android_id' => str_random(64)])
    ];

    Classroom::whereName('3ro Basico')->first()->players()->saveMany($players);
	}

}