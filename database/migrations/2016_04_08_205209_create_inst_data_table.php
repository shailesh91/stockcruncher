<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstDataTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('inst_data', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->integer('stock_id')->unsigned();
				$table->dateTime('inst_datetime');
				$table->double('inst_price', 15, 8);
				$table->bigInteger('volume');
				$table->primary(['stock_id', 'inst_datetime']);
				$table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('inst_data');
	}
}
