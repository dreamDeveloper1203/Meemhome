<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class InventoryStatusController extends Controller
{

    /**
     * Update the specified resource status in storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Inventory $inventory): JsonResponse
    {
        $inventory->update([
            'is_active' => !$inventory->is_active
        ]);
        Cache::forget(Inventory::CACHE_KEY);
        return $this->jsonResponse();
    }
}