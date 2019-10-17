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
Route::group(['middleware' => 'auth:api', 'cors'], function(){
	Route::post('details', 'Auth\UserController@details');
	Route::post('update/user', 'Auth\UserController@updateProfile');
	Route::get('my/currencies', 'UserWalletController@myCurrencies');
	Route::post('loadwallet', 'UserWalletController@loadWallet');
	Route::post('search/other/user', 'UserWalletController@searchOtherUser');
	Route::post('update/user/dp/{id}', 'Auth\UserController@updateProfilePicture');
	Route::post('update/user/basic/{id}', 'Auth\UserController@updateProfileBasic');
	Route::post('update/user/account/{id}', 'Auth\UserController@updateProfileAccount');
	Route::post('update/user/pin/{id}', 'Auth\UserController@updateProfilePin');
	// Route::post('user/trade/{id}', 'UserWalletController@postUserTrade');
	Route::post('user/transfer/{id}', 'UserWalletController@userTransfer');
	Route::post('user/trade', 'UserWalletController@postUserTrade');
	Route::post('getUser/trade/{id}/{trader_id}', 'UserWalletController@getUserTrade'); // first param is user_trade pk, then id of trade poster
	Route::post('filter/byCurrency', 'ViewController@filterHistorybyCurrency');
	Route::get('trade/list', 'ViewController@tradeList');
	Route::get('trade/dashboard', 'ViewController@tradeListDashboard');
	Route::get('check/trader/balance/{id}/{trader_id}', 'UserWalletController@checkTheTraderBalance');
	Route::post('monitor/user/transaction', 'UserWalletController@monitorUserTransaction');
	Route::post('avoid/user/transfer/{id}','UserWalletController@avoidUserTransfer');
	Route::post('delete/myTrade/{id}', 'UserWalletController@destroy');

	Route::post('wallet/create', 'URequestController@createRequest');
	Route::post('wallet/list', 'URequestController@getRequests');
});

Route::group(['namespace' => 'Auth', 'middleware' => 'auth:api', 'prefix' => 'password'], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});
