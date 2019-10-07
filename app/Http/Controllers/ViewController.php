<?php

namespace App\Http\Controllers;

use Request;
use App\UserHistory;
use App\UserTrades;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{

	public function tradeList()
	{
		$trades = UserTrades::with('trader_info')->where('status', 1)->get();

		return response()->json($trades);
	}

    public function tradeListDashboard()
    {
        $trades = UserTrades::with('trader_info')->where('status', 1)->take(3)->get();

        return response()->json($trades);
    }

    public function filterHistorybyCurrency()
    {
    	$currencyFilter = Request::get('currencyFilter');

    	/*if(empty($currencyFilter)){
    		$default = UserHistory::with('user_sender','user_receiver')->where('currency_trade','btc')->orWhere('currency_request','btc')->orderBy('created_at', 'DESC')->get();

            return response()->json(['results' => $default]);
    	}*/
    	$results = UserHistory::with('user_sender','user_receiver')->where('currency_trade',$currencyFilter)->orWhere('currency_request',$currencyFilter)->orderBy('created_at', 'DESC')->get();


    	return response()->json(['results' => $results]);
    }
}
