<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHistDataTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('hist_data', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->integer('stock_id')->unsigned();
			$table->date('hist_date');
			$table->double('open_price', 15, 8);
			$table->double('close_price', 15, 8);
			$table->double('min_price', 15, 8);
			$table->double('max_price', 15, 8);
			$table->double('adj_close', 15, 8);
			$table->bigInteger('volume');
			$table->primary(['stock_id', 'hist_date']);
			$table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('hist_data');
	}
}