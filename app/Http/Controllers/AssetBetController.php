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
     * @param Asset $asset
     * @return \Illuminate\Http\Response
     */
    public function index(Asset $asset)
    {
//        is anyone logged in can access this index?
        $bets = UserBet::where('asset_id', $asset->id);
        $user = Request::get('user', null);

        if ($user)
            $bets = $bets->where('user_id', $user);

        return $bets->orderBy('timestamp', 'desc')->paginate(10);
    }

    public function store(Asset $asset, AssetBetStoreRequest $request)
    {
//        return $request->user();
//        before ma-store ang amount dapat i-check kung naa paba siyay kwarta.
//        unsaon pud pag retrieve inig madaog ang beter?
        $this->authorize('create', UserBet::class);
        $validated = $request->validated();
        $validated['asset_id'] = $asset;
        $validated['user_id'] = $request->user()->id;
        $validated['timestamp'] = Carbon::now();

        $bet = UserBet::create($validated);
        return response()->json($bet, Response::HTTP_CREATED);
    }
}
