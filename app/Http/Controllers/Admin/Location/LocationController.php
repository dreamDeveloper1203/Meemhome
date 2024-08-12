<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequests\LocationStoreRequest;
use App\Http\Requests\LocationRequests\LocationUpdateRequest;
use App\Http\Resources\LocationResources\LocationResourceCollection;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->checkPermission('area_access');
        return $this->jsonResponse(['data' => new LocationResourceCollection(
            Location::orderBy('name', 'ASC')->get()
        )]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LocationRequests\LocationStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LocationStoreRequest $request): JsonResponse
    {
        Location::create([
            'name' => $request->name,
            'is_active' => true,
        ]);
        Cache::forget(Location::CACHE_KEY);
        return $this->jsonResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LocationRequests\LocationUpdateRequest  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LocationUpdateRequest $request, Location $location): JsonResponse
    {
        $location->update([
            'name' => $request->name
        ]);
        Cache::forget(Location::CACHE_KEY);
        return $this->jsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Location $location): JsonResponse
    {
        $location->delete();
        Cache::forget(Location::CACHE_KEY);
        return $this->jsonResponse();
    }
}