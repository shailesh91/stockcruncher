<?php

namespace App\Http\Controllers;

use App\Stocks;
use Auth;
use Illuminate\Http\Request;
use App\InstData;
use App\HistData;

class AjaxQueryController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth');
	}

	public function getStockNames(Request $request) {
		$term       = strtolower($request->input('term'));
		$categories = $request->input('category');
		$data       = array();

		if($categories == null){
			$results = Stocks::select('stock_name', 'stock_symbol', 'id')
				->where(function ($query) use ($term) {
					$query->where('stock_name', 'LIKE', '%'.$term.'%')
						->orWhere('stock_symbol', 'LIKE', '%'.$term.'%');
				})->get();
		}else{
			$results = Stocks::select('stock_name', 'stock_symbol', 'id')
				->whereIn('category_id', array_values($categories))
				->where(function ($query) use ($term) {
					$query->where('stock_name', 'LIKE', '%'.$term.'%')
						->orWhere('stock_symbol', 'LIKE', '%'.$term.'%');
				})->get();
		}
		$user = Auth::user();
		foreach ($results as $result) {
			if (!in_array($result->id, $user->portfolio_items->pluck('stock_id')->toArray())) {
				$data[] = array('id' => $result->id, 'stock_name' => $result->stock_name, 'stock_symbol' => $result->stock_symbol);
			}
		}

		return response()->json($data);
	}
}
