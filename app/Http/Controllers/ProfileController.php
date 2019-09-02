<?php

namespace App\Http\Controllers;

use App\UserProfile;
use Request;
use Input;
use DB;

// use Illuminate\Http\Request;

class ProfileController extends Controller {

  public function login () {

  }

  public function register () {
    $profile = Request::all();
    UserProfile::create($profile);

    return response()->json(compact( 'profile' ));
  }
}
