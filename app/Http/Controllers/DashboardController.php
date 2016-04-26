<?php

namespace App\Http\Controllers;
use Auth;
use App\Categories;
use App\PortfolioItems;
use App\Stocks;
use App\InstData;
use App\HistData;
use GuzzleHttp\Client;

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
		$portfolioItems = PortfolioItems::where('user_id',$user->id)->with('stocks')->get();
		$pfi = array();
		foreach ($portfolioItems as $pi) {
			if($pi->stocks->init){


			$last = HistData::where('stock_id',$pi->stock_id)
							->orderBy('hist_date','DESC')
							->first();

			$today = date("Y-m-d"); 
			$tendaysback = date("Y-m-d", strtotime("-10 day", strtotime($today)));

			$max10days = HistData::where('stock_id',$pi->stock_id)
							->where('hist_date','<=',$today)
							->where('hist_date','>=',$tendaysback)
							->max('max_price');

			$oneyearback = date("Y-m-d", strtotime("-1 year", strtotime($today)));
			$average1year = HistData::where('stock_id',$pi->stock_id)
							->where('hist_date','<=',$today)
							->where('hist_date','>=',$oneyearback)
							->avg('close_price');

			//call do prediction

			$client = new Client();
			$res = $client->get('http://localhost:8080/stockcruncher-ee/data/getKFPrediction?stockid='.$pi->stock_id.'&symbol='.$pi->stocks->stock_symbol, ['verify' => false]);

            if($res->getStatusCode() == "200"){
                $body = $res->getBody();
                $s = $body->getContents();
                $status = json_decode($s, true);
            }

			$pfi[$pi->stock_id ] = array("last"=>$last->close_price,"max10days"=>$max10days,"average1year"=>$average1year,'kfprediction'=>$status);
			}
		}
		return view('dashboard.index')->with(['categories' => $categories,'pf'=>$portfolioItems,'pfi'=>$pfi]);
	}

	public function getStock($id,$stocksymbol) {
		$stock = Stocks::where('id',$id)->with('category','inst_data', 'hist_data')->get();
		
		$last = HistData::where('stock_id',$id)
							->orderBy('hist_date','DESC')
							->first();

		$today = date("Y-m-d"); 
		$tendaysback = date("Y-m-d", strtotime("-10 day", strtotime($today)));
		$max10days = HistData::where('stock_id',$id)
						->where('hist_date','<=',$today)
						->where('hist_date','>=',$tendaysback)
						->max('max_price');

		$oneyearback = date("Y-m-d", strtotime("-1 year", strtotime($today)));
		$average1year = HistData::where('stock_id',$id)
						->where('hist_date','<=',$today)
						->where('hist_date','>=',$oneyearback)
						->avg('close_price');

		//KF prediction
		$client = new Client();
		$res = $client->get('http://localhost:8080/stockcruncher-ee/data/getKFPrediction?stockid='.$id.'&symbol='.$stocksymbol, ['verify' => false]);
        if($res->getStatusCode() == "200"){
            $body = $res->getBody();
            $s = $body->getContents();
            $kfstatus = json_decode($s, true);
        }

		//SVM prediction
		$res = $client->get('http://localhost:8080/stockcruncher-ee/data/getSVMPrediction?stockid='.$id.'&symbol='.$stocksymbol, ['verify' => false]);
        if($res->getStatusCode() == "200"){
            $body = $res->getBody();
            $s = $body->getContents();
            $svmstatus = json_decode($s, true);
        }

        //ANN prediction
		$res = $client->get('http://localhost:8080/stockcruncher-ee/data/getAnnPrediction?stockid='.$id.'&symbol='.$stocksymbol, ['verify' => false]);
        if($res->getStatusCode() == "200"){
            $body = $res->getBody();
            $s = $body->getContents();
            $annstatus = json_decode($s, true);
        }

		$pfi = array("last"=>$last->close_price,"max10days"=>$max10days,"average1year"=>$average1year,'kfprediction'=>$kfstatus,'svmprediction'=>$svmstatus,'annprediction'=>$annstatus);


		return view('dashboard.stock')->with(['st' => $stock,'pfi'=>$pfi]);
	}
}
