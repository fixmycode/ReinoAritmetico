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
		Schema::create('problem_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');

			$table->integer('max_difficulty');
			$table->datetime('started')->nullable();
			$table->datetime('ended')->nullable();

		});

		Schema::create('problems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('problem', 128);
			$table->string('correct_answer');
			$table->integer('difficulty');
			$table->integer('problem_type_id')->unsigned();
			$table->datetime('started')->nullable();
			$table->datetime('ended')->nullable();

			$table->foreign('problem_type_id')
	      		->references('id')
	      		->on('problem_type')
	      		->onDelete('cascade');
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
		Schema::drop("problem_type");
	}

}
