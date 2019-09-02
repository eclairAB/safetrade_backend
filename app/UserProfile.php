<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserProfile extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'user_profiles';
    protected $fillable = [
        'user_display_pic',
        'user_level',
        'user_name',
        'user_password',
        'email',
        'name_first',
        'name_last',
        'contact_no',
        'birth_date',
        'zip_code',
        'city',
        'address',
        'country',
        'state',
        'transaction_pin'
    ];
}
