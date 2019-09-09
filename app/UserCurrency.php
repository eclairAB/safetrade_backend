<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCurrency extends Model
{
    protected $table = 'user_currencies';
    protected $fillable = [
        'user_id',
		'btc',
		'eth',
		'xrp',
		'ltc',
		'bch',
		'eos',
		'bnb',
		'usdt',
		'bsv',
		'trx'
	];
}
