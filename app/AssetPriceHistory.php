<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPriceHistory extends Model
{
    protected $fillable = ['asset_id', 'timestamp', 'price'];
    protected $casts = [
        'timestamp' => 'timestamp'
    ];

    public $timestamps = false;

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }
}
