<?php
namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PortfolioItems;
use App\Stocks;

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
        $client = new Client();
        $flag = true;
        $status = array();
        foreach ((array) $stocks as $stock_id){
            PortfolioItems::firstOrCreate(["user_id"=>$user->id,"stock_id"=> $stock_id]);
            $st = Stocks::where('id',$stock_id)->first();
            $res = $client->get('http://localhost:8080/stockcruncher-ee/data/initstock?stockid='.$stock_id.'&symbol='.$st->stock_symbol, ['verify' => false]);
            if($res->getStatusCode() == "200"){
                $body = $res->getBody();
                $s = $body->getContents();
                $status = json_decode($s, true);
                $flag = true;
            }else{
                $flag = flase;
                
            }
        }
        if( $flag){
            Flash::success('Stocks added to portfolio!'.$status['messagel'].'!');    
        }else{
            Flash::error('Unable to Add Stock! Please try Again!');
        }
        return redirect()->route('dashboard');
    }
}
