<?php

namespace App\Http\Controllers;

use Request;
use App\User;
use App\UserTrades;
use App\UserTranfer;
use App\UserRequest;
use App\UserHistory;
use App\UserCurrency;
use App\Events\TradePosted;
use App\Events\TradeRemoved;
use App\Events\WalletUpdated;
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

                        broadcast(new WalletUpdated());
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

    public function userTransfer($id)
    {
    	$user = Auth::user();
        $trades = UserTrades::where('status',1)->where('user_id',$user->id)->get();
        $get_balance = UserCurrency::where('user_id',$user->id)->first();

        if($trades->count() > 0){

            foreach ($trades as $trade) {
                
                $wallet = $trade->trade_currency;
                $current_wallet = doubleval($get_balance->$wallet);
                $amount[] = doubleval($trade->trade_amount);
                $data = array_sum($amount);

            }

            $my_balance = doubleval($current_wallet);
            $my_current_trades = doubleval($data);
            $diff_balance = ($my_balance - $my_current_trades);
            $onhold_balance = doubleval($diff_balance);
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
               $current_wallet = doubleval($get_balance->$wallet);
               $amount[] = doubleval($trade->request_amount);
               $data = array_sum($amount);
           }   

           $my_balance = doubleval($current_wallet);
           $my_current_trades = doubleval($data);
           $diff_balance = ($my_balance - $my_current_trades);
           $onhold_balance = doubleval($diff_balance);
           if(Request::get('trade_amount') > $onhold_balance){
               return response()->json(['message' => 'Currently, you have a pending trade. It will exceed your balance with this transaction.']);
           }else{
               return $this->monitorUserTransaction();
           }
       }else{
           return $this->monitorUserTransaction();
       }
       /*if($this->ifAllowTransaction($amount, $minuend->$currency, $currency, $id_user)) {

       }*/
       return response()->json(['message' => 'Trades Pending']);
    }

    public function getUserTrade($id, $trader_id)
    {
        // SENDER PART START
            //get the trader info and trader trade propose.
            $trader = UserTrades::with('trader_info')->find($id);

            //check the trader's wallet.
            $trader_wallet = UserCurrency::where('user_id',$trader->trader_info->id)->first();

            //to get the specific trader's wallet dynamically.
            $request = $trader->request_currency;
            $trade = $trader->trade_currency;

            //pass to variable all object used in condition to make it short.
            $trader_debit = doubleval($trader->request_amount);
            $trader_wal1 = doubleval($trader_wallet->$request);

            $trader_credit = doubleval($trader->trade_amount);
            $trader_wal2 = doubleval($trader_wallet->$trade);

        // SENDER PART END

        // RECEIVER PART START
            //get the user info.
            $user = Auth::user();
            $receiver = UserCurrency::where('user_id',$user->id)->first();

            $receiver_credit = doubleval($receiver->$request);
        // RECEIVER PART END
        // dd($trader_wal2);
        //compare the value of the user's balance to the requested amount and trade value.
        //if request_amount > balance OR trade_amount > balance
        if($trader_debit > $trader_wal1 || $trader_credit > $trader_wal2){
            return response()->json(['message' => 'Currently, the trader has have not enough balance to proceed with this transaction.']);
        }elseif($trader_debit > $receiver_credit){
            return response()->json(['message' => 'You dont have enough balance to proceed this transaction.']);
        }else{
            return $this->checkTheTraderBalance($id, $trader_id, $id);
        }
    }


    public function checkTheTraderBalance($id, $trader_id, $post_id)
    {
        //i check kung kinsa ang authenticated na user.
        //sa diri na part kung ikaw ang authenticated usually ikaw ang maka maka dawat sa trade.
        $receiver = Auth::user();

        //data sa maka dawat sa trade (receiver).
        $your_balance = UserCurrency::where('user_id',$receiver->id)->first();

        //data sa nag create ug trade (sender).
        $trader_balance = UserCurrency::where('user_id',$trader_id)->first();

        //data sa gi select nimo na trade sa baba gi doubleval para makuha ang exact form sa value.
        $selected_trade = UserTrades::where('status',1)->find($id);
        $sender_amount = doubleval($selected_trade->trade_amount);
        $receiver_amount = doubleval($selected_trade->request_amount);

        //checker kung unsa ang gusto sa wallet sa creator ug trade.
        $trader_wallet_currency = $selected_trade->trade_currency;

        if($selected_trade->user_id > 2 && $selected_trade->user_id < 8){
            return response()->json(['message' => 'This trade is already acquired by other user.']);
        }else{
            //1st for security purpose mag input ug transaction pin para maka trade kung mali Incorrect Transaction Pin!.
            if(Request::get('transaction_pin') == $receiver->transaction_pin){
                
                //2nd pag ang itrade sa sender na value kay greater than sa imong balance(receiver) Invalid Request Trade!
                if(doubleval($selected_trade->trade_amount) > $your_balance[$trader_wallet_currency]){
                    return response()->json(['message' => 'Invalid Request Trade!']);
                }else{
                    // else proceed sa trade transaction.
                    $transfer = new UserHistory;
                    $transfer->sender_id = $trader_id;
                    $transfer->receiver_id = $receiver->id;
                    $transfer->amount = $selected_trade->request_amount;
                    $transfer->amount_two = $selected_trade->trade_amount;
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

                        broadcast(new TradeRemoved($post_id));
                        broadcast(new WalletUpdated());
                        return response()->json(compact('transfer'));
                    }
                }
            }else{
                return response()->json(['message' => 'Incorrect Transaction Pin!']);
            }
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

                    broadcast(new TradePosted($trade));
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

        if(Request::get('transaction_pin') == $user->transaction_pin) {

            $usertrade = UserTrades::where('user_id',$user->id)->find($id);

            broadcast(new TradeRemoved($usertrade));
            $delete = $usertrade->delete();
            
            return response()->json(['message' => 'You successfully deleted your trade post.']);
        }
        else {
            return response()->json(['message' => 'Incorrect Transaction Pin!']);
        }
    }

    function ifAllowTransaction ($amount, $balance, $currency, $id_user) {

      // will return true if pending trade amount is not greater than request amount
  
      $userTrade = UserTrades::where('user_id', $id_user)->where('trade_currency', $currency)->get();
      $userRequest = UserRequest::where('user_id', $id_user)->where('currency', $currency)->get();
      $arr = [];
      
      foreach ($userTrade as $item) {
        array_push($arr, $item->trade_amount);
      }
      foreach ($userRequest as $item) {
        array_push($arr, $item->amount);
      }
      $pendingTotal = $balance - array_sum($arr);
      return $pendingTotal >= $amount ? true : false;
    }
}