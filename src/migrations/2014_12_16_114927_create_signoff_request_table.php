<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignoffRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coanda_signoffrequests', function ($table) {

			$table->increments('id');
			$table->integer('version_id');
			$table->integer('version');
			$table->integer('page_id');
			$table->string('page_name');
			$table->integer('requested_by');
			$table->integer('actioned_by');
			$table->string('status');
			$table->text('message');

			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('coanda_signoffrequests');
	}

}
