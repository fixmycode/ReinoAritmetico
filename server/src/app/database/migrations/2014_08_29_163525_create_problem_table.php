<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		

		Schema::create('problems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('problem', 128);
			$table->string('correct_answer');
			$table->integer('difficulty');
			$table->datetime('started')->nullable();
			$table->datetime('ended')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("problems");
	}

}
