<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\UploadTrait;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use UploadTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'type',
        'user_display_pic',
        'name_first',
        'name_last',
        'contact_no',
        'birth_date',
        'zip_code',
        'city',
        'address',
        'country',
        'state',
        'transaction_pin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getImageAttribute()
    {
       return $this->user_display_pic;
    }

    public function bets()
    {
        return $this->hasMany(UserBet::class);
    }

    public function betAmount()
    {
        return $this->hasOne(BetAmount::class);
    }

    public function assets()
    {
        return $this->hasMany(UserAsset::class);
    }
}
