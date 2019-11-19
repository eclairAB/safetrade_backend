<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Request;

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
        $bets = UserBet::where('asset_id', $asset->id);

        $user = Request::get('user', null);

        if ($user) {
            $bets = $bets->where('user_id', $user);
        }
        return $bets->orderBy('timestamp', 'desc')->paginate(10);
    }
}
