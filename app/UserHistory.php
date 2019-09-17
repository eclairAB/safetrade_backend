<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'user_histories';
    protected $fillable = [
        'sender_id',
		'receiver_id',
		'amount',
		'transaction_option',
		'currency_trade',
		'currency_request'
	];

	public function user_sender()
	{
		return $this->hasOne('App\User', 'id','sender_id');
	}

	public function user_receiver()
	{
		return $this->hasOne('App\User', 'id','receiver_id');
	}
}
