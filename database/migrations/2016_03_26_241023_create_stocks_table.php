<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStocksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('stocks', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->increments('id');
				$table->string('stock_name', 200)->unique();
				$table->string('stock_symbol', 10)->unique();
				$table->integer('category_id')->unsigned();
				$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('stocks');
	}
}
