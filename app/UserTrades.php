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
		'trade_currency',
		'status',
		'bot_image',
		'bot_name'
	];

	public function trader_info()
	{
		return $this->hasOne('App\User', 'id','user_id');
	}
}
