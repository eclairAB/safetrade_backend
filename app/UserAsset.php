<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAsset extends Model
{
    protected $fillable =[
        'user_id',
        'asset_id',
        'amount',
    ];
}
