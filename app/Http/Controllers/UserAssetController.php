<?php

namespace App\Http\Controllers;

use App\UserAsset;
use App\UserBet;
use \Illuminate\Http\Request;
use Auth;

class UserAssetController extends Controller
{
    /**
     * Display lists of users' assets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if($user->is_superuser) {
            $users_with_assets = UserAsset::paginate(15);

            return response()->json(compact('users_with_assets'));
        }
        else
            return response()->json(['message'=>'You are not allowed to access this page.']);
    }

    /**
     * Admin create new user's asset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if($user->is_superuser){
            try {
                UserAsset::create([
                    'user_id' => $request->user_id,
                    'asset_id' => $request->asset_id,
                    'amount' => $request->amount,
                ]);
            }catch (\Exception $e){
                return response()->json(['message'=>'Failed to create.', 'error'=>$e]);
            }
            return response()->json(['message' => 'Successfully created.']);
        }
        else
            return response()->json(['message'=>'You are not allowed to access this page.']);
    }

//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        return $id;
//    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //find id
        // return if not found

        //add or subtract amount
        //return if the result amount will be negative
        //update the amount
        //return the amount

//        return ([$request,$id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Soft delete?
        //If permanently deleted, should we save a history log?
    }
}
