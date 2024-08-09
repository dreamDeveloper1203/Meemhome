<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LocationStatusController extends Controller
{

    /**
     * Update the specified resource status in storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Location $location): JsonResponse
    {
        $location->update([
            'is_active' => !$location->is_active
        ]);
        Cache::forget(Location::CACHE_KEY);
        return $this->jsonResponse();
    }
}