<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'user_trades';
    protected $fillable = [
        'user_id',
		'request_amount',
		'trade_amount',
		'request_currency',
		'trade_currency'
	];
}
