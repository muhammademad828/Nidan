<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationService $locationService) {}

    public function setRegion(Request $request): RedirectResponse
    {
        $request->validate(['region_id' => 'required|exists:regions,id']);
        $this->locationService->setRegion($request->integer('region_id'));
        return back();
    }
}
