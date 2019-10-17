<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
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
}
