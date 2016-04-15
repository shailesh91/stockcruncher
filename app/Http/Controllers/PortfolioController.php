<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PortfolioItems;
use Auth,Session,Flash;

class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addStocks(Request $request){
        $user = Auth::user();
        $stocks = $request->input('stocks');
        foreach ((array) $stocks as $stock_id){
          PortfolioItems::firstOrCreate(["user_id"=>$user->id,"stock_id"=> $stock_id]);
        }

        Flash::success('Stocks added to portfolio!!');
        return redirect()->route('dashboard');

    }
}
