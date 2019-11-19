<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetPriceHistory;

class AssetPriceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        return AssetPriceHistory::where('asset_id', $asset->id)
            ->orderBy('timestamp', 'desc')
            ->paginate(1000);
    }
}
