<?php

namespace App\Http\Controllers;

use App\UserAsset;
use \Illuminate\Http\Request;
use App\Http\Requests\UserAssetStoreRequest;

class UserAssetController extends Controller
{

    /**
     * Display lists of users' assets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-any',UserAsset::class);
        $users_with_assets = UserAsset::paginate(15);
        return response()->json(compact('users_with_assets'), 200);
    }

    /**
     * Admin create new user's asset.
     *
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAssetStoreRequest $request)
    {
        $this->authorize('create',UserAsset::class);
        $validated = $request->validated();
        UserAsset::create($validated);
        return response()->json(['message' => 'Successfully created.'],201);
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
//
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
     * @param  UserAsset $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', UserAsset::class);
        //find row id
        $row = UserAsset::findOrFail($id);
        \Validator::make($request->all(),[
            'add' => 'required|numeric'
        ]);
        //add or subtract amount
        $total= $row->amount + $request->add;
        //return an error if the result amount will be negative
        if($total<0)
            return response()->json(['message'=>'Cannot continue transaction. Insufficient fund.'],400);
        //update the amount
        $row->amount = $total;
        $row->save();
        //return the amount
        return response()->json(['message' => 'Transaction saved successfully.',
            'data'=>$row], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', UserAsset::class);
        //If permanently deleted, should we save a history log?
        $row = UserAsset::findOrFail($id);
        //must create a log for deleted user's asset here?
        $row->delete();
        return response()->json(['message'=>'User asset successfully deleted.'],200);
    }
}
