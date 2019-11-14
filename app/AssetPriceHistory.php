<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPriceHistory extends Model
{
    public $timestamps = false;

    public function asset() {
        return $this->belongsTo('App\Asset');
    }
}
