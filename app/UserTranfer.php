<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTranfer extends Model
{
        protected $table = 'user_tranfers';
        protected $fillable = [
            'sender_id',
    		'receiver_id',
    		'amount',
    		'currency'
    	];
}
