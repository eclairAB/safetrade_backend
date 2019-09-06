<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller {

  public $successStatus = 200;

  public function login() { 
    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
      $user = Auth::user(); 
      $success['token'] =  $user->createToken('MyApp')-> accessToken; 
      return response()->json(['success' => $success], $this-> successStatus); 
    } 
    else {
      return response()->json(['error'=>'Unauthorised'], 401); 
    } 
  }

  public function register(Request $request) { 
    $validator = Validator::make($request->all(), [ 
        'username' => 'required', 
        'email' => 'required|email', 
        'password' => 'required', 
        'c_password' => 'required|same:password',
        'user_level' => 'required',
        'name_first' => 'required',
        'name_last' => 'required',
        'contact_no' => 'required',
        'birth_date' => 'required',
        'zip_code' => 'required',
        'city' => 'required',
        'address' => 'required',
        'country' => 'required',
        'state' => 'required'
    ]);

  if ($validator->fails()) { 
      return response()->json(['error'=>$validator->errors()], 401);            
    }

  $input = $request->all(); 
    $input['password'] = bcrypt($input['password']); 
    $user = User::create($input); 
    $success['token'] =  $user->createToken('MyApp')-> accessToken; 
    $success['username'] =  $user->username;
    $success['user_level'] = $user->user_level;
    $success['name_first'] = $user->name_first;
    $success['name_last'] = $user->name_last;
    $success['contact_no'] = $user->contact_no;
    $success['birth_date'] = $user->birth_date;
    $success['zip_code'] = $user->zip_code;
    $success['city'] = $user->city;
    $success['address'] = $user->address;
    $success['country'] = $user->country;
    $success['state'] = $user->state;
    return response()->json(['success'=>$success], $this-> successStatus); 
  }

  public function details() { 
    $user = Auth::user(); 
    return response()->json(['success' => $user], $this-> successStatus); 
  }
}
