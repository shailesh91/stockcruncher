<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioItems extends Model {
	use SoftDeletes;
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'portfolio_items';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['user_id', 'stock_id'];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * Get the user that owns the portfolio items.
	 */
	public function user() {
		return $this->belongsTo('App\User');
	}

	/**
	 * Get the stock that owns the portfolio items.
	 */
	public function stocks() {
		return $this->belongsTo('App\Stocks');
	}

}
