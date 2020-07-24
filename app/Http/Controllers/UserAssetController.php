<?php

namespace App\Http\Controllers;

use App\UserAsset;
use Request;
use Auth;

class UserAssetController extends Controller
{
    /**
     * Display a listing of the user assets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if($user->is_superuser) {

            $user_with_assets = UserAsset::paginate(15);

            return response()->json(compact('user_with_assets'));
        }
        else
            return response()->json(['message'=>'You are not allowed to access this page.']);

    }

    /**
     * Admin create new users asset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Request::all();
        $user = Auth::user();

        if($user->is_superuser){
            $ifexist = UserAsset::where('user_id',$request->user_id)
                ->where('asset_id',$request->asset_id)
                ->firstOrFail();
            return $ifexist;

            $request=Request::all();
            UserAsset::create($request);

            return response()->json(['message'=>'Successfully create,']);
        }
        else
            return response()->json(['message'=>'You are not allowed to access this page.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
