<?php

namespace App\Http\Controllers;
use Auth;
use App\Categories;
use App\PortfolioItems;
use App\Stocks;
use App\InstData;

class DashboardController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$categories = Categories::all();
		$user = Auth::user();
		$portfolioItems = PortfolioItems::where('user_id',$user->id)->get();
		return view('dashboard.index')->with(['categories' => $categories,'pf'=>$portfolioItems]);
	}

	public function getStock($id,$stocksymbol) {
		$stock = Stocks::where('id',$id)->with('category','inst_data', 'hist_data')->get();
		//dd($stock);
		return view('dashboard.stock')->with(['st' => $stock]);
	}
}
