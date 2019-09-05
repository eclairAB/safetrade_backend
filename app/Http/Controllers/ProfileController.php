<?php

namespace App\Http\Controllers;

use App\UserProfile;
use Request;
use Input;
use DB;

// use Illuminate\Http\Request;

class ProfileController extends Controller {

  public function login ($user_name, $user_password) {

    return DB::table('user_profiles')
             ->where('user_name', $user_name)
             ->where('user_password', $user_password)
             ->value('id');
  }

  public function signup () {
    $profile = Request::all();
    $user = UserProfile::create($profile);

    return response()->json(compact('user'));
  }
}
