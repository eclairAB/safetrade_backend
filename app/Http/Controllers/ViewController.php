<?php

namespace App\Http\Controllers;

use Request;
use App\UserTrades;
use App\UserHistory;

class ViewController extends Controller
{

	public function tradeList()
	{
		$trades = UserTrades::with('trader_info')->get();

		return response()->json($trades);
	}

    public function filterHistorybyCurrency()
    {
    	$keyword = Request::get('currency');

    	if(empty($keyword)){
    		$default = UserHistory::with('user_sender','user_receiver')->where('currency_trade','btc')->orWhere('currency_request','btc')->get();

    		return response()->json(['results' => $default]);
    	}
    	$results = UserHistory::with('user_sender','user_receiver')->where('currency_trade',$keyword)->orWhere('currency_request',$keyword)->get();


    	return response()->json(['results' => $results]);
    }
}
