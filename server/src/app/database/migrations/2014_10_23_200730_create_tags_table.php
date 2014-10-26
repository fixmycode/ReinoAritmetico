<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->datetime('started')->nullable();
			$table->datetime('ended')->nullable();
		});

		Schema::create('problem_tag', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('tag_id')->unsigned();
			$table->integer('problem_id')->unsigned();


			$table->foreign('tag_id')
						->references('id')
						->on('tags');

			$table->foreign('problem_id')
						->references('id')
						->on('problems');

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
		Schema::drop('problem_tag');
		Schema::drop('tags');
	}

}
