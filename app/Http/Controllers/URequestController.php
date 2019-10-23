<?php

namespace App\Http\Controllers;

use App\UserCurrency;
use App\UserHistory;
use App\UserRequest;
use App\UserTrades;
use Auth;
use DB;
use Input;
use Request;

class URequestController extends Controller
{
  public function createRequest () {
    $user = Auth::user();
    $id_user = Request::get('user_id');
    $amount = Request::get('amount');
    $currency = strtolower(Request::get('currency'));
    $minuend = UserCurrency::where('id', $id_user)->first();

    if(Request::get('transaction_pin') == $user->transaction_pin) {

      if(Request::get('type') == 'Reload'){

        $request = Request::all();
        $data = UserRequest::create($request);
        return response()->json(compact('data'));
      }
      else {

        if($this->ifAllowWithdraw($amount, $minuend->$currency, $currency, $user->id, true)) {

          if($this->ifBalanceAvailable($minuend, $currency, $amount)) {

            $request = Request::all();
            $data = UserRequest::create($request);
            return response()->json(compact('data'));
          }
          return response()->json(['message' => 'Insufficient user balance']);
        }
        return response()->json(['message' => 'Trades Pending']);
      }
    }
    else return response()->json(['message' => 'Incorrect Transaction Pin!']);
  }


  public function getRequests () {

    $filter = Request::get('filter');

    if ($filter == 'All') {

      $data = UserRequest::with('user_data')->get();
      return response()->json($data);
    }
    else {

      $data = UserRequest::with('user_data')->where('type', $filter)->get();
      return response()->json($data);
    }
  }


  public function approveRequest () {

    $user = Auth::user();
    $id_request = Request::get('id');
    $id_user = Request::get('user_id');
    $currency = strtolower(Request::get('currency'));
    $amount = Request::get('amount');
    $type = Request::get('type');
    $userCurrency = UserCurrency::where('id', $id_user)->first();

    if(Request::get('transaction_pin') == $user->transaction_pin) {

      if($type == 'Reload'){

        $userCurrency->increment($currency, $amount);
        UserRequest::where('id', '=', $id_request)->delete();

        $this->addToHistory($id_user, $amount, $type, $currency); // adds to transaction to history
        return response()->json(['message' => 'reload success']);
      }
      else {

        if($this->ifAllowWithdraw($amount, $userCurrency->$currency, $currency, $id_user, false)) {

          if($this->ifBalanceAvailable($userCurrency, $currency, $amount)) {

            $userCurrency->decrement($currency, $amount);
            UserRequest::where('id', '=', $id_request)->delete();

            $this->addToHistory($id_user, $amount, $type, $currency); // adds to transaction to history
            return response()->json(['message' => 'withdraw success']);
          }
          return response()->json(['message' => 'Insufficient user balance']);
        }
        return response()->json(['message' => 'Trades Pending']);
      }
    }
    else return response()->json(['message' => 'Incorrect Transaction Pin!']);
  }


  public function deleteRequest () {

    $user = Auth::user();
    if(Request::get('transaction_pin') == $user->transaction_pin) {
      
      UserRequest::where('id', '=', Request::get('id'))->delete();
      return response()->json(['message' => 'deleted']);
    }
    else return response()->json(['message' => 'Incorrect Transaction Pin!']);
  }

// ------------------------------------------------------------------------------------------------

  public function addToHistory ($receiver_id, $amount, $type, $currency) {

    $userHistory = new UserHistory;
    $userHistory->sender_id = 1;
    $userHistory->receiver_id = $receiver_id;
    $userHistory->amount = $amount;
    $userHistory->transaction_option = $type;
    $userHistory->currency_request = $currency;

    $userHistory->save();
  }

  public function ifBalanceAvailable ($minuend, $currency, $subtrahend) {

    // will return true if requested amount for withdraw is not greater than wallet balance

    $difference = doubleval($minuend->$currency) - doubleval($subtrahend);
    return $difference >= 0 ? true : false;
  }

  public function ifAllowWithdraw ($amount, $balance, $currency, $id_user, $isCreate) {

    // will return true if pending trade amount is not greater than request amount for withdraw

    $userTrade = UserTrades::where('user_id', $id_user)->where('trade_currency', $currency)->get();
    $userRequest = UserRequest::where('user_id', $id_user)->where('currency', $currency)->get();
    $arr = [];
    
    foreach ($userTrade as $item) {
      array_push($arr, $item->trade_amount);
    }
    if ($isCreate) {
      foreach ($userRequest as $item) {
        array_push($arr, $item->amount);
      } 
    }
    $pendingTotal = $balance - array_sum($arr);
    return $pendingTotal >= $amount ? true : false;
  }
}
