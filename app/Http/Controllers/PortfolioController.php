<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PortfolioItems;

use Auth;
use Session; 
use Flash;
    


class PortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addStocks(Request $request){
        $user = Auth::user();
        $stocks = $request->input('stocks');
        //$client = new GuzzleHttp\Client();
        foreach ((array) $stocks as $stock_id){
          PortfolioItems::firstOrCreate(["user_id"=>$user->id,"stock_id"=> $stock_id]);
          /*$res = $client->request('GET', 'https://localhost:8080/stockcruncher-ee/data/initstock', [
            'stockid' => $stock_id,
            'symbol' => 

            ]);
            if($res->getStatusCode() == "200"){

            }*/
        }
        Flash::success('Stocks added to portfolio! Data being added now!');
        return redirect()->route('dashboard');

    }
}
