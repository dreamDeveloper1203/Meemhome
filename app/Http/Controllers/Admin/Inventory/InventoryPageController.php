<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryPageController extends Controller
{
    //
    /**
     * Show Category Page.
     *
     *  @return \Illuminate\View\View
     */
    public function show(): View|RedirectResponse
    {
        $this->checkPermission('area_access');
        return view('admin.inventories.show');
    }
}