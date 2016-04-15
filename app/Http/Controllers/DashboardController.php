<?php

namespace App\Http\Controllers;

use App\Categories;

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
		return view('dashboard.index')->with(['categories' => $categories]);
		;
	}
}
