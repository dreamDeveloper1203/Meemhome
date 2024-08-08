<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequests\InventoryStoreRequest;
use App\Http\Requests\InventoryRequests\InventoryUpdateRequest;
use App\Http\Resources\InventoryResources\InventoryResourceCollection;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->checkPermission('area_access');
        return $this->jsonResponse(['data' => new InventoryResourceCollection(
            Inventory::orderBy('name', 'ASC')->get()
        )]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\InventoryStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InventoryStoreRequest $request): JsonResponse
    {
        Inventory::create([
            'name' => $request->name,
            'is_active' => true,
        ]);
        Cache::forget(Inventory::CACHE_KEY);
        return $this->jsonResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequests\InventoryUpdateRequest  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InventoryUpdateRequest $request, Inventory $inventory): JsonResponse
    {
        $inventory->update([
            'name' => $request->name
        ]);
        Cache::forget(Inventory::CACHE_KEY);
        return $this->jsonResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Inventory $inventory): JsonResponse
    {
        $inventory->delete();
        Cache::forget(Inventory::CACHE_KEY);
        return $this->jsonResponse();
    }
}