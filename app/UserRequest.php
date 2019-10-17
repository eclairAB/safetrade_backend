<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    protected $table = 'user_requests';
    protected $fillable = [
        'user_id',
		'currency',
		'amount',
		'type'
	];

	public function user_data()
	{
		return $this->hasOne('App\User', 'id','user_id');
	}
}
