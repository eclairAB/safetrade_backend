<?php

namespace App\Http\Controllers;

use Auth;
use Request;
use App\UserHistory;
use App\UserTrades;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{

	public function tradeList()
	{
		$trades = UserTrades::with('trader_info')->where('status', 1)->orderBy('created_at', 'DESC')->get();

		return response()->json($trades);
	}

    public function tradeListDashboard()
    {
        $trades = UserTrades::with('trader_info')->where('status', 1)->take(3)->get();

        return response()->json($trades);
    }

    public function filterHistorybyCurrency()
    {

        $data = [];
        $user = Auth::user();

    	$currencyFilter = Request::get('currencyFilter');

    	/*if(empty($currencyFilter)){
    		$default = UserHistory::with('user_sender','user_receiver')->where('currency_trade','btc')->orWhere('currency_request','btc')->orderBy('created_at', 'DESC')->get();

            return response()->json(['results' => $default]);
    	}*/
    	$results = UserHistory::with('user_sender','user_receiver')->where('currency_trade',$currencyFilter)->orWhere('currency_request',$currencyFilter)->get();

        foreach ($results as $result) {
            if($user->id == $result->sender_id OR $user->id == $result->receiver_id){
                $arr = [
                    'sender_name' => $result->user_sender->username,
                    'sender_dp' => $result->user_sender->user_display_pic,
                    'receiver_name' => $result->user_receiver->username,
                    'receiver_dp' => $result->user_receiver->user_display_pic,
                    'transaction_option' => $result->transaction_option,
                    'currency_trade' => $result->currency_trade,
                    'amount' => $result->amount,
                    'created_at' => $result->created_at,
                    'currency_request' => $result->currency_request,
                    'currency_trade' => $result->currency_trade
                ];
                array_push($data, $arr);
            }
        }


    	return response()->json(['results' => $data]);
    }
}