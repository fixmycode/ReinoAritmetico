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
			$table->float('headX')->nullable()->default(0);
			$table->float('headY')->nullabel()->default(0);
			$table->float('handX')->nullabel()->default(0);
			$table->float('handY')->nullabel()->default(0);
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
