<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $vendors = Vendor::withCount('products')->orderBy('id', 'desc')->paginate(20);
        $totalVendors = Vendor::count();
        $activeVendors = Vendor::active()->count();
        $totalProducts = \App\Models\Product::count();

        return view('admin.vendors.index', compact('vendors', 'totalVendors', 'activeVendors', 'totalProducts'));
    }

    public function store(UpsertVendorRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        Vendor::create($data);
        return back()->with('success', 'Vendor created.');
    }

    public function update(UpsertVendorRequest $request, Vendor $vendor): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $vendor->update($data);
        return back()->with('success', 'Vendor updated.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();
        return back()->with('success', 'Vendor deleted.');
    }
}
