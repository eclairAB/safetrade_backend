<?php

namespace App\Http\Controllers;

use App\Asset;

class AssetPriceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($assetId)
    {
        //
        $asset = Asset::findOrFail($assetId);
        return $asset->prices;
    }
}
