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
			
		});

		Schema::create('problems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('question', 128);
			$table->string('answer');
			$table->integer('difficulty');
			$table->integer('problem_type_id')->unsigned();

			$table->foreign('problem_type_id')
	      ->references('id')->on('problem_type');
		});

		Schema::create('game_problem', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('game_id')->unsigned();
			$table->integer('problem_id')->unsigned();

			$table->foreign('problem_id')
	      ->references('id')->on('problems');

	    $table->foreign('game_id')
	      ->references('id')->on('games');  
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop("game_problem");
		Schema::drop("problems");
		Schema::drop("problem_type");
	}

}