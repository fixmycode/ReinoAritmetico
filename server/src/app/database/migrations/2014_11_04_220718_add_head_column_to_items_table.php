<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddHeadColumnToItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('items', function(Blueprint $table)
		{
			$table->float('headX')->nullabel();
			$table->float('headY')->nullabel();
			$table->float('handX')->nullabel();
			$table->float('handY')->nullabel();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('items', function(Blueprint $table)
		{
			$table->dropColumn(['headX', 'headY', 'handX', 'handY']);
		});
	}

}
