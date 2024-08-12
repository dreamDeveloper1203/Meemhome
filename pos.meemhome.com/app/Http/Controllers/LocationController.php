<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Traits\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LocationController extends Controller
{

    use Availability;
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {

        return view("locations.index");
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view("locations.create");
    }
    /**
     * Show resources.
     * 
     * @return \Illuminate\View\View
     */
    public function edit(Location $location): View
    {
        return view("locations.edit", [
            'location' => $location
        ]);
    }
    /**
     * Delete resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();
        return Redirect::back()->with("success", __("Deleted"));
    }

    /**
     * Show resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string'],
        ]);

        $location = Location::create([
            'name' => $request->name,
            'is_active' => $this->isAvailable($request->status),
        ]);


        return Redirect::back()->with("success", __("Created"));
    }

    /**
     * update resources.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string'],
        ]);

        $location->update([
            'name' => $request->name,
            'is_active' => $this->isAvailable($request->status),
        ]);
        return Redirect::back()->with("success", __("Updated"));
    }
}