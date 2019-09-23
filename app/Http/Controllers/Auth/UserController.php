<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\UserCurrency;
use App\User;
use Auth;
use Image;
use Hash;

class UserController extends Controller {

  public $successStatus = 200;

  public function login() { 
    if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
      $success = Auth::user(); 
      $success['token'] =  $success->createToken('MyApp')-> accessToken; 
      return response()->json(['success' => $success, 'id' => $success->id], $this-> successStatus);
    } 
    else {
      return response()->json(['error'=>'Unauthorised'], 401); 
    } 
  }

  public function getProfile($uid) {
    // return User::find($uid);

    $users = User::select($uid)
     ->select([
          'id',
          'username',
          'email',
          'password',
          'user_display_pic',
          'user_level',
          'name_first',
          'name_last',
          'contact_no',
          'birth_date',
          'zip_code',
          'city',
          'address',
          'country',
          'state',
     ])->find($uid);

    return response()->json(compact('users'));
  }

  public function register(Request $request) { 
    $validator = Validator::make($request->all(), [ 
        'username' => 'required|unique:users', 
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

    $wallet = new UserCurrency;
    $wallet->user_id = $user->id;
    $wallet->btc = '0.0000000000';
    $wallet->eth = '0.0000000000';
    $wallet->xrp = '0.0000000000';
    $wallet->ltc = '0.0000000000';
    $wallet->bch = '0.0000000000';
    $wallet->eos = '0.0000000000';
    $wallet->bnb = '0.0000000000';
    $wallet->usdt = '0.0000000000';
    $wallet->bsv = '0.0000000000';
    $wallet->trx = '0.0000000000';
    $wallet->cash = '0.0000000000';
    $wallet->save();
    return response()->json(['success'=>$success, 'id'=>$user->id], $this-> successStatus); 
  }

  public function details() { 
    $user = Auth::user(); 
    return response()->json(['success' => $user], $this-> successStatus); 
  }

  public function updateProfilePicture(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [ 
      'user_display_pic' => 'required',
    ]);
    if ($validator->fails()) { 
      return response()->json(['error'=>$validator->errors()], 401);            
    }

    $user = User::find($id);
    $user->user_display_pic = $request->input('user_display_pic');
    $user->save();

    return response()->json(compact('user'));
  }

  public function updateProfileBasic(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [ 
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

    $user = User::find($id);

    $user->name_first = $request->input('name_first');
    $user->name_last = $request->input('name_last');
    $user->contact_no = $request->input('contact_no');
    $user->birth_date = $request->input('birth_date');
    $user->zip_code = $request->input('zip_code');
    $user->city = $request->input('city');
    $user->address = $request->input('address');
    $user->country = $request->input('country');
    $user->state = $request->input('state');

    $user->save();

    return response()->json(compact('user'));
  }

  public function updateProfileAccount(Request $request, $id)
  {
    $users = User::find($id);
      if(empty($request->password)){
        $validator = Validator::make($request->all(), [ 
          'username' => 'required', 
          'email' => 'required|email'
        ]);
        if ($validator->fails()) { 
          return response()->json(['error'=>$validator->errors()], 401);            
        }

        $user = User::find($id);
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->save();

        return response()->json(compact('user'));
      }
      else{
        $validator = Validator::make($request->all(), [ 
          'username' => 'required', 
          'email' => 'required|email', 
          'password' => 'required', 
          'c_password' => 'required|same:password'  
      ]);
      if ($validator->fails()) { 
        return response()->json(['error'=>$validator->errors()], 401);            
      }

      $user = User::find($id);
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->password = bcrypt($request->input('password'));
      $user->save();

      return response()->json(compact('user'));
    }
  }

  public function updateProfilePin(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [ 
      'transaction_pin' => 'required',
    ]);
    if ($validator->fails()) {  
      return response()->json(['error'=>$validator->errors()], 401);            
    }

    $user = User::find($id);
    $user->transaction_pin = $request->input('transaction_pin');
    $user->save();

    return response()->json(compact('user'));
  }
}
