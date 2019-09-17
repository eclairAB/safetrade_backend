<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTrades extends Model
{
    protected $table = 'user_trades';
    protected $fillable = [
        'user_id',
		'request_amount',
		'trade_amount',
		'request_currency',
		'trade_currency'
	];

	public function trader_info()
	{
		return $this->hasOne('App\User', 'id','user_id');
	}

	// public function user_receiver()
	// {
	// 	return $this->hasOne('App\User', 'id','receiver_id');
	// }
}
