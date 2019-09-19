<?php

namespace App\Http\Controllers;

use Request;
use App\User;
use App\UserTrades;
use App\UserTranfer;
use App\UserHistory;
use App\UserCurrency;
use Auth;
use DB;

class UserWalletController extends Controller
{
    public function myCurrencies()
    {
    	$sender = Auth::user();
    	$user_currency = UserCurrency::with('user')->where('user_id',$sender->id)->first();
    	return response()->json(compact('user_currency'));
    }

    public function loadWallet()
    {
    	$user_currency_req = Request::all();
    	$user_currency = UserCurrency::create($user_currency_req);

    	return response()->json(compact('user_currency'));
    }

    public function searchOtherUser()
    {
    	$keyword = Request::get('keyword');
    	$search_user = User::where('username','LIKE', '%' .$keyword. '%')->orWhere('name_first','LIKE', '%' .$keyword. '%')->orWhere('name_last','LIKE', '%' .$keyword. '%')->get();

    	return response()->json(compact('search_user'));
    }

    public function userTranfer($id)
    {
    	$sender = Auth::user();

    	$sender_balance = UserCurrency::where('user_id',$sender->id)->first();
    	$receiver_balance = UserCurrency::where('user_id',$id)->first();
    	if(Request::get('transaction_pin') == $sender->transaction_pin){
    		if($sender_balance[Request::get('currency_trade')] >= 0 || $sender_balance[Request::get('currency_trade')] >= 00.00){
    			if(Request::get('amount') > $sender_balance[Request::get('currency_trade')]){
    				return response()->json(['message' => 'Invalid Request Transfer!']);
    			}else{
	    			$transfer = new UserHistory;
	    			$transfer->sender_id = $sender->id;
	    			$transfer->receiver_id = $id;
	    			$transfer->amount = Request::get('amount');
	    			$transfer->transaction_option = Request::get('transaction_option');
	    			$transfer->currency_trade = Request::get('currency_trade');
	    			$transfer->currency_request = Request::get('currency_request');
                    
					$sender_balance->decrement(Request::get('currency_trade'), Request::get('amount'));
					$receiver_balance->increment(Request::get('currency_trade'), Request::get('amount'));
					
					$transfer->save();
					return response()->json(compact('transfer'));
    			}
    		}else{
    			return response()->json(['message' => 'Insufficient Balance!']);
    		}
    	}else{
    		return response()->json(['message' => 'Incorrect Transaction Pin!']);
    	}
    }

    public function postUserTrade()
    {
    	$sender = Auth::user();

    	$sender_balance = UserCurrency::where('user_id',$sender->id)->first();
    	if(Request::get('transaction_pin') == $sender->transaction_pin){
    		if($sender_balance[Request::get('trade_currency')] >= 0 || $sender_balance[Request::get('trade_currency')] >= 00.00){
    			if(Request::get('trade_amount') > $sender_balance[Request::get('trade_currency')]){
    				return response()->json(['message' => 'Invalid Request Trade!']);
    			}else{
    				$trade = new UserTrades;

    				$trade->user_id = $sender->id;
    				$trade->request_amount = Request::get('request_amount');
    				$trade->trade_amount = Request::get('trade_amount');
    				$trade->request_currency = Request::get('request_currency');
    				$trade->trade_currency = Request::get('trade_currency');

    				$trade->save();
    				return response()->json(compact('trade'));
    			}
    		}else{
    			return response()->json(['message' => 'Insufficient Balance!']);
    		}
    	}else{
    		return response()->json(['message' => 'Incorrect Transaction Pin!']);
    	}
    }

    public function getUserTrade($id)
    {
    	$receiver = Auth::user();

    	$your_balance = UserCurrency::where('user_id',$receiver->id)->first();
    	$trader_balance = UserCurrency::where('user_id',$id)->first();
    	if(Request::get('transaction_pin') == $receiver->transaction_pin){
    		if(Request::get('currency_trade') > $your_balance[Request::get('currency_request')]){
    			return response()->json(['message' => 'Invalid Request Trade!']);
    		}else{
    			$transfer = new UserHistory;
    			$transfer->sender_id = $sender->id;
    			$transfer->receiver_id = $id;
    			$transfer->amount = Request::get('amount');
    			$transfer->transaction_option = Request::get('transaction_option');
    			$transfer->currency_trade = Request::get('currency_trade');
    			$transfer->currency_request = Request::get('currency_request');
				$sender_balance->decrement(Request::get('currency_trade'), Request::get('amount'));
				$receiver_balance->increment(Request::get('currency_trade'), Request::get('amount'));
				
				$transfer->save();
				return response()->json(compact('transfer'));
    		}
    	}else{
    		return response()->json(['message' => 'Incorrect Transaction Pin!']);
    	}
    }
}