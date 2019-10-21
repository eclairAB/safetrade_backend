<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCurrency extends Model
{
    protected $table = 'user_currencies';
    protected $fillable = [
        'user_id',
        'cash_bal',
		'btc',
		'eth',
		'xrp',
		'ltc',
		'bch',
		'eos',
		'bnb',
		'usdt',
		'bsv',
		'trx',
		'cash'
	];

	public function user()
	{
		return $this->hasOne('App\User', 'id','user_id');
	}
}
