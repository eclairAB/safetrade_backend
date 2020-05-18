<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBet extends Model
{
    use \LaravelTreats\Model\Traits\HasCompositePrimaryKey;

    public $timestamps = false;

    protected $primaryKey = ['user_id', 'asset_id', 'timestamp'];

    protected $fillable = [
        'user_id',
        'asset_id',
        'amount',
        'will_go_up',
        'timestamp',
    ];

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
