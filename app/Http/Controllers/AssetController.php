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
     * @return Response
     * @throws
     */
    public function index()
    {
        $this->authorize('view-any',Asset::class);
        $assets = Asset::paginate(10);
        return response()->json($assets,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AssetStoreRequest $request
     * @return Response
     * @throws
     */
    public function store(AssetStoreRequest $request)
    {
        $this->authorize('create', Asset::class);
        $validated = $request->validated();
        $asset = Asset::create($validated);
        return response()->json($asset, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Asset $asset
     * @return Response
     * @throws
     */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);
        return response()->json($asset,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AssetStoreRequest $request
     * @param Asset $asset
     * @return Response
     * @throws
     */
    public function update(AssetStoreRequest $request, Asset $asset)
    {
        $this->authorize('update', $asset);
        $validated = $request->validated();
        $asset->update($validated);
        return response()->json($asset, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Asset $asset
     * @return Response
     * @throws
     */
    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);
        $asset->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
