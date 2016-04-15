<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stocks extends Model {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'stocks';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['stock_name', 'stock_symbol', 'category_id'];

	/**
	 * Get the category that owns the stock.
	 */
	public function category() {
		return $this->belongsTo('App\Categories');
	}

	/**
	 * Get the PortfolioItems for the stock.
	 */
	public function portfolio_items() {
		return $this->hasMany('App\PortfolioItems');
	}
}
