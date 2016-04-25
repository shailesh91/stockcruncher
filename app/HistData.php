<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistData extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'hist_data';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['stock_id', 'hist_date', 'open_price', 'close_price', 'min_price', 'max_price', 'adj_close', 'volume'];

	/**
	 * Get the stock that owns the portfolio items.
	 */
	public function stocks() {
		return $this->belongsTo('App\Stocks','stock_id');
	}
}
