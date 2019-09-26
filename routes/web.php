<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Message;
use App\Events\MessageSent;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/messages', function () {
    $messages = Message::take(200)->pluck('content');
    return $messages;
});

Route::post('/messages', function () {
    $message = new Message();
    $content = request('message');
    $message->content = $content;
    $message->save();

    event(new MessageSent($content));

    return $content;
});
