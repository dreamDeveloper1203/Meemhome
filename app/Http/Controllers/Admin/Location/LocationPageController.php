<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationPageController extends Controller
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
        return view('admin.locations.show');
    }
}