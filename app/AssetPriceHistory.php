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
    // protected $dateFormat = 'Y-m-d H:i:s.uO';
    protected $dateFormat = 'Y-m-d H:i:sO';

    public $timestamps = false;

    public function asset()
    {
        return $this->belongsTo('App\Asset');
    }
}
