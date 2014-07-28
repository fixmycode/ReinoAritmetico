<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolCourses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schools', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			
		});

		Schema::create('courses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			
		});
	}

	
	public function down()
	{
		Schema::drop('schools');
		Schema::drop('courses');
	}

}
