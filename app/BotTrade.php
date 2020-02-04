<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotTrade extends Model
{
    protected $table = 'bot_trades';
    protected $fillable = [
        'wallet_one',
		'wallet_two',
		'min_one',
		'max_one',
		'min_two',
		'max_two'
	];
}
