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
}
