<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstData extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inst_data';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['stock_id', 'inst_datetime', 'inst_price', 'volume'];

	/**
	 * Get the stock that owns the portfolio items.
	 */
	public function stocks() {
		return $this->belongsTo('App\Stocks','stock_id');
	}
}
