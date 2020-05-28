<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\AssetStoreRequest;
use App\Asset;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::all();
        return response()->json($assets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AssetStoreRequest $request)
    {
        $validated = $request->validated();
        $asset = Asset::create($validated);
        return response()->json($asset, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $asset
     * @return \Illuminate\Http\Response
     */
    public function show($asset)
    {
        return response()->json(Asset::findOrFail($asset));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(AssetStoreRequest $request, $asset)
    {
        $asset = Asset::findOrFail($asset);
        $validated = $request->validated();
        $asset->update($validated);
        return response()->json($asset, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy($asset)
    {
        $asset = Asset::findOrFail($asset);
        $asset->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
