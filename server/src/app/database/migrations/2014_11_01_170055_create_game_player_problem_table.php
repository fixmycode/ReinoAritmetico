<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamePlayerProblemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('game_player_problem', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('game_player_id')->unsigned()->index();
			$table->foreign('game_player_id')->references('id')->on('game_player')->onDelete('cascade');
			$table->integer('problem_id')->unsigned()->index();
			$table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
			$table->string("answer");
			$table->float("time_elapsed");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('game_player_problem');
	}

}
