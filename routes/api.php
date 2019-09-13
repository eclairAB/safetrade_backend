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

Route::post('login', 'Auth\UserController@login');
Route::get('getProfile/{uid}', 'Auth\UserController@getProfile');

Route::post('register', 'Auth\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::get('my/currencies/{id}', 'UserWalletController@myCurrencies');
	Route::post('details', 'Auth\UserController@details');
	Route::post('loadwallet', 'UserWalletController@loadWallet');
	Route::post('search/other/user', 'UserWalletController@searchOtherUser');
	Route::post('update/user/dp', 'Auth\UserController@updateProfilePicture');
	Route::post('update/user/basic', 'Auth\UserController@updateProfileBasic');
	Route::post('update/user/account', 'Auth\UserController@updateProfileAccount');
	Route::post('update/user/pin', 'Auth\UserController@updateProfilePin');
	Route::post('user/trade/{id}', 'UserWalletController@postUserTrade');
	Route::post('user/transfer/{id}', 'UserWalletController@userTranfer');
});
