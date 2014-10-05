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
			$table->datetime('started');
			$table->datetime('ended')->nullable();
			
		});

		Schema::create('problems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('problem', 128);
			$table->string('correct_answer');
			$table->integer('difficulty');
			$table->integer('problem_type_id')->unsigned();
			$table->datetime('started');
			$table->datetime('ended')->nullable();

			$table->foreign('problem_type_id')
	      ->references('id')->on('problem_type');
		});

		Schema::create('game_problem', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('game_id')->unsigned();
			$table->integer('problem_id')->unsigned();
			$table->datetime('started');
			$table->datetime('ended')->nullable();

			$table->foreign('problem_id')
	      ->references('id')->on('problems');

	    $table->foreign('game_id')
	      ->references('id')->on('games');  
		});

		Schema::create('answers', function(Blueprint $table){
			$table->increments('id');
			$table->integer('answer_time');
			$table->integer('player_id')->unsigned();
			$table->integer('problem_id')->unsigned();
			$table->string('answer_selected');
			$table->boolean('answer_state');

			$table->foreign('player_id')->references('id')->on('players');
			$table->foreign('problem_id')->references('id')->on('problems');


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
