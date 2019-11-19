<?php

namespace App\Http\Controllers;

use App\UserBet;
use App\Asset;

class AssetBetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        return UserBet::where('asset_id', $asset->id)
            ->orderBy('timestamp', 'desc')
            ->paginate(10);
    }
}
