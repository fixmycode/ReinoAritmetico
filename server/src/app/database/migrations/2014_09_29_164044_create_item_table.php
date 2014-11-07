<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_type', function(Blueprint $table){
			$table->increments('id');
			$table->string('nombre');
			$table->string('description');
		});

		Schema::create('items', function(Blueprint $table) {
			$table->increments('id');
			$table->string('nombre');
			$table->string('description');
			$table->integer('price',0);
			$table->string('image_path')->nullable();
			$table->integer('item_type_id')->unsigned();
			$table->integer('character_type_id')->nullable()->unsigned();

			$table->foreign('item_type_id')
						->references('id')
						->on('item_type')
						->onDelete('cascade');


			$table->foreign('character_type_id')
			      ->references('id')
						->on('character_type')
						->onDelete('cascade');

			$table->datetime('started')->nullable();
			$table->datetime('ended')->nullable();
		});

		Schema::create('item_player', function(Blueprint $table){
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->integer('player_id')->unsigned();

			$table->foreign('item_id')
			      ->references('id')
						->on('items')
						->onDelete('cascade');

			$table->foreign('player_id')
						->references('id')
						->on('players')
						->onDelete('cascade');
		});

    Schema::table('players', function($table) {

        $table->foreign('armor_id')
        		->references('id')
            ->on('items');

        $table->foreign('weapon_id')
        		->references('id')
            ->on('items');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('players', function($table)
		{
		    $table->dropForeign('players_armor_id_foreign');
		    $table->dropForeign('players_weapon_id_foreign');
		});
		Schema::drop('item_player');
		Schema::drop('items');
		Schema::drop('item_type');
	}

}
