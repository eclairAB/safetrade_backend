<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

<<<<<<< HEAD
Route::get('/users', function (Request $request) {
    return response()->json(['name' => 'Behrang No']);
});

// Route::post('signup', 'ProfileController@signup');
// Route::get('login/{user_name, user_password}', 'ProfileController@login');

// Route::prefix('v1')->group(function(){
//  Route::post('auth_login', 'AuthController@auth_login');
//  Route::post('auth_register', 'AuthController@auth_register');
//  Route::group(['middleware' => 'auth:api'], function(){
//  Route::post('auth_getUser', 'AuthController@auth_getUser');
//  });
// });


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'API\UserController@details');
});
=======

Route::post('login', 'Auth\UserController@login');
Route::post('register', 'Auth\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'Auth\UserController@details');
});
>>>>>>> 86affd5b515696b5007b315a2bfc9d9cc1e75833
