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
            'eth' => number_format($user_currency->eth, 10),
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

    public function avoidUserTransfer($id)
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

    public function userTranfer($id)
    {
    	$user = Auth::user();
        $trades = UserTrades::where('status',1)->where('user_id',$user->id)->get();
        $get_balance = UserCurrency::where('user_id',$user->id)->first();

        if($trades->count() > 0){

            foreach ($trades as $trade) {
                
                $wallet = $trade->trade_currency;
                $current_wallet = number_format($get_balance->$wallet,10);
                $amount[] = number_format($trade->trade_amount, 10);
                $data = array_sum($amount);

            }

            $my_balance = number_format($current_wallet,10);
            $my_current_trades = number_format($data,10);
            $diff_balance = ($my_balance - $my_current_trades);
            $onhold_balance = number_format($diff_balance,10);
            if(Request::get('amount') > $onhold_balance){
                return response()->json(['message' => 'Currently, you have an active trade, proceeding this transfer will exceed your balance.']);
            }else{
                return $this->avoidUserTransfer($id);
            }
        }else{
            return $this->avoidUserTransfer($id);
        }
    }

    public function postUserTrade()
    {
       $user = Auth::user();
       $trades = UserTrades::where('status',1)->where('user_id',$user->id)->get();
       $get_balance = UserCurrency::where('user_id',$user->id)->first();
   
       if($trades->count() > 0){

           foreach ($trades as $trade) {

               $wallet = $trade->trade_currency;
               $current_wallet = number_format($get_balance->$wallet,10);
               $amount[] = number_format($trade->request_amount, 10);
               $data = array_sum($amount);
           }   

           $my_balance = number_format($current_wallet,10);
           $my_current_trades = number_format($data,10);
           $diff_balance = ($my_balance - $my_current_trades);
           $onhold_balance = number_format($diff_balance,10);
           if(Request::get('trade_amount') > $onhold_balance){
               return response()->json(['message' => 'Currently, you have a pending trade. It will exceed your balance with this transaction.']);
           }else{
               return $this->monitorUserTransaction();
           }
       }else{
           return $this->monitorUserTransaction();
       }
    }

    public function getUserTrade($id, $trader_id)
    {
    	//i check kung kinsa ang authenticated na user.
        //sa diri na part kung ikaw ang authenticated usually ikaw ang maka maka dawat sa trade.
        $receiver = Auth::user();

        //data sa maka dawat sa trade (receiver).
        $your_balance = UserCurrency::where('user_id',$receiver->id)->first();

        //data sa nag create ug trade (sender).
        $trader_balance = UserCurrency::where('user_id',$trader_id)->first();

        //data sa gi select nimo na trade sa baba gi number_format para makuha ang exact form sa value.
        $selected_trade = UserTrades::where('status',1)->find($id);
        $sender_amount = number_format($selected_trade->trade_amount, 10);
        $receiver_amount = number_format($selected_trade->request_amount,10);

        //checker kung unsa ang gusto sa wallet sa creator ug trade.
        $trader_wallet_currency = $selected_trade->trade_currency;

        //1st for security purpose mag input ug transaction pin para maka trade kung mali Incorrect Transaction Pin!.
        if(Request::get('transaction_pin') == $receiver->transaction_pin){
            
            //2nd pag ang itrade sa sender na value kay greater than sa imong balance(receiver) Invalid Request Trade!
            if(number_format($selected_trade->trade_amount,10) > $your_balance[$trader_wallet_currency]){
                return response()->json(['message' => 'Invalid Request Trade!']);
            }else{
                // else proceed sa trade transaction.
                $transfer = new UserHistory;
                $transfer->sender_id = $trader_id;
                $transfer->receiver_id = $receiver->id;
                $transfer->amount = $selected_trade->request_amount;
                $transfer->transaction_option = Request::get('transaction_option');
                $transfer->currency_trade = $selected_trade->trade_currency;
                $transfer->currency_request = $selected_trade->request_currency;

                //1st i deduct sa imong wallet ang request amount sa creator.
                //ang format (wallet,amount).
                $your_balance->decrement($selected_trade->request_currency, $receiver_amount);

                //2nd ma add sa creator's wallet ang gipang trade nimo(receiver) na amount.
                $trader_balance->increment($selected_trade->request_currency, $receiver_amount);

                //3rd ma add sa imong wallet(receiver) ang gi trade sa creator.
                $your_balance->increment($selected_trade->trade_currency, $sender_amount);

                //4th ma ma deduct sa creator's wallet ang ipang trade niya.
                $trader_balance->decrement($selected_trade->trade_currency, $sender_amount);

                //if good na tanan ma save sa history ang transaction then ma update anh status sa trade. i update kay ang ipa show lang kay status 1.
                if($transfer->save()){
                    $selected_trade->status = 0;
                    $selected_trade->save();
                     return response()->json(compact('transfer'));
                }
            }
        }else{
            return response()->json(['message' => 'Incorrect Transaction Pin!']);
        }
    }


    public function checkTheTraderBalance($id)
    {
        //get the trader info and trader trade propose.
        $trader = UserTrades::with('trader_info')->find($id);

        //check the trader's wallet.
        $trader_wallet = UserCurrency::where('user_id',$trader->trader_info->id)->first();

        //to get the specific trader's wallet dynamically.
        $request = $trader->request_currency;
        $trade = $trader->trade_currency;

        //pass to variable all object used in condition to make it short.
        $trader_debit = number_format($trader->request_amount,10);
        $trader_wal1 = number_format($trader_wallet->$request,10);

        $trader_credit = number_format($trader->trade_amount,10);
        $trader_wal2 = number_format($trader_wallet->$trade,10);

        //compare the value of the user's balance to the requested amount and trade value.
        if($trader_debit > $trader_wal1 || $trader_credit > $trader_wal2){
            $trader->status = 0;
            $trader->save();

            return response()->json(['message' => 'Currently, the trader has have not enough balance to proceed with this transaction.']);
        }
    }

    public function monitorUserTransaction()
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

    public function destroy($id)
    {
        $user = Auth::user();
        $usertrade = UserTrades::where('user_id',$user->id)->find($id);

        $delete = $usertrade->delete();

        return response()->json(['message' => 'You successfully deleted your trade post.']);
    }    
}