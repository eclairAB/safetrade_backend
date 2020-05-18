<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetPriceHistory extends Model
{
    use \LaravelTreats\Model\Traits\HasCompositePrimaryKey;
    protected $primaryKey = ['asset_id', 'timestamp'];

    protected $fillable = ['asset_id', 'timestamp', 'price'];
    protected $casts = [
        'timestamp' => 'timestamp',
    ];

    public $timestamps = false;

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }
}
