<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserBet;
use App\Http\Requests\UserBetStoreRequest;

class UserBetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bets = UserBet::where('user_id', $request->user()->id)
            ->orderBy('timestamp', 'desc')
            ->paginate(20);
        return response()->json($bets);
    }
}
