<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Request;
use Symfony\Component\HttpFoundation\Response;

use App\UserBet;
use App\Asset;
use App\Http\Requests\AssetBetStoreRequest;

class AssetBetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $asset)
    {
        Asset::findOrFail($asset);
        $bets = UserBet::where('asset_id', $asset);

        $user = Request::get('user', null);

        if ($user) {
            $bets = $bets->where('user_id', $user);
        }
        return $bets->orderBy('timestamp', 'desc')->paginate(10);
    }

    public function store(int $asset, AssetBetStoreRequest $request)
    {
        Asset::findOrFail($asset);
        $validated = $request->validated();
        $validated['asset_id'] = $asset;
        $validated['user_id'] = $request->user()->id;
        $validated['timestamp'] = Carbon::now();

        $bet = UserBet::create($validated);
        return response()->json($bet, Response::HTTP_CREATED);
    }
}
