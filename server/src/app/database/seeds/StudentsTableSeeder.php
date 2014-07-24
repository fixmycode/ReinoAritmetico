<?php

class StudentsTableSeeder extends Seeder {

	public function run()
	{
    DB::table('students')->delete();

    $students = [
      new Student(["name" => "Jon Snow", 'android_id' => str_random(64)])
    ];

    Classroom::whereName('3ro Basico')->first()->students()->saveMany($students);
	}

}