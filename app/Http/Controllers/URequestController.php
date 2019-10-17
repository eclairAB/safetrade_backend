<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\UserCurrency;
use App\UserHistory;
use App\UserRequest;
use Auth;
use DB;
use Input;
use Request;

class URequestController extends Controller
{
  public function createRequest () {
    $user = Auth::user();

    if(Request::get('transaction_pin') == $user->transaction_pin) {
      
      $request = Request::all();
      $data = UserRequest::create($request);

      return response()->json(compact('data'));
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

    if(Request::get('transaction_pin') == $user->transaction_pin) {

      if($type == 'Reload'){

        $addend = UserCurrency::where('id', $id_user)->first();
        $sum = number_format($addend->$currency,10) + number_format($amount,10);

        DB::table('user_currencies')->where('id', $id_user)->update([$currency => $sum]);
        UserRequest::where('id', '=', $id_request)->delete();

        $this->addToHistory($id_user, $amount, $type, $currency);

        return response()->json(['message' => 'reload success']);
      }
      else {

        $minuend = UserCurrency::where('id', $id_user)->first();
        $difference = number_format($minuend->$currency,10) - number_format($amount,10);

        if($difference >= 0) {

          DB::table('user_currencies')->where('id', $id_user)->update([$currency => $difference]);
          UserRequest::where('id', '=', $id_request)->delete();

          $this->addToHistory($id_user, $amount, $type, $currency);

          return response()->json(['message' => 'withdraw success']);
        }
        return response()->json(['message' => 'Insufficient user balance']);
      }
    }
    else return response()->json(['message' => 'Incorrect Transaction Pin!']);
  }


  public function addToHistory ($receiver_id, $amount, $type, $currency) {

    $userHistory = new UserHistory;
    $userHistory->sender_id = 1;
    $userHistory->receiver_id = $receiver_id;
    $userHistory->amount = $amount;
    $userHistory->transaction_option = $type;
    $userHistory->currency_request = $currency;

    $userHistory->save();
  }
}
