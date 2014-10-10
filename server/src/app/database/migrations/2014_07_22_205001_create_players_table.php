<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('character_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('uid');
			$table->string('sex');
			$table->string('name', 64);
		});


		Schema::create('players', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('credits')->default(0);
			$table->string('android_id', 64);
			$table->integer('classroom_id')->unsigned();
			$table->integer('character_type_id')->unsigned();
			$table->integer('client_id')->unsigned();

			$table->foreign('client_id')->references('id')
							->on('clients');

			$table->foreign('character_type_id')->references('id')
							->on('character_type');

			$table->foreign('classroom_id')
	      ->references('id')->on('classrooms')
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

		Schema::drop('players');
		Schema::drop('character_type');
	}

}
