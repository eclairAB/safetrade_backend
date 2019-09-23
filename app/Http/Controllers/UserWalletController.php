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
        $data = [];
    	$sender = Auth::user();
    	$user_currency = UserCurrency::with('user')->where('user_id',$sender->id)->first();

        $array = [
            'btc' => number_format($user_currency->btc, 10),
            'etc' => number_format($user_currency->etc, 10),
            'xrp' => number_format($user_currency->xrp, 10),
            'ltc' => number_format($user_currency->ltc, 10),
            'bch' => number_format($user_currency->bch, 10),
            'eos' => number_format($user_currency->eos, 10),
            'bnb' => number_format($user_currency->bnb, 10),
            'usdt' => number_format($user_currency->usdt, 10),
            'bsv' => number_format($user_currency->bsv, 10),
            'trx' => number_format($user_currency->trx, 10),
            'cash' => number_format($user_currency->cash, 10),
        ];
        array_push($data, $array);
        return response()->json(compact('data'));
    }

    public function loadWallet()
    {
    	$user_currency_req = Request::all();
    	$user_currency = UserCurrency::create($user_currency_req);

    	return response()->json(compact('user_currency'));
    }

    public function searchOtherUser()
    {
        $data = [];
        $user = Auth::user();
    	$keyword = Request::get('keyword');

        $search_users = User::where('username','LIKE', '%' .$keyword. '%')->orWhere('name_first','LIKE', '%' .$keyword. '%')->orWhere('name_last','LIKE', '%' .$keyword. '%')->get();

        foreach ($search_users as $search_user) {
            if($search_user->id != $user->id){
                $array = [
                    'id' => $search_user->id,
                    'username' => $search_user->username,
                    'user_display_pic' => $search_user->user_display_pic,
                    'name_first'    => $search_user->name_first,
                    'name_last' => $search_user->name_last,
                ];
                array_push($data,$array);
            }
        }
        return response()->json(compact('data'));
        
    }

    public function userTranfer($id)
    {
    	$sender = Auth::user();
        $receiver_credentials = User::find($id);
    	$sender_balance = UserCurrency::where('user_id',$sender->id)->first();
    	$receiver_balance = UserCurrency::where('user_id',$id)->first();
        if($sender->id != $id){
            if(Request::get('transaction_pin') == $sender->transaction_pin AND Request::get('email') == $receiver_credentials->email){
                if($sender_balance[Request::get('currency_trade')] >= 0 || $sender_balance[Request::get('currency_trade')] >= 00.00){
                    if(Request::get('amount') > $sender_balance[Request::get('currency_trade')] || Request::get('amount') <= 0){
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
                return response()->json(['message' => 'Incorrect Credentials!']);
            }
        }
        return response()->json(['message' => 'Invalid Request Transaction!']);
    	
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