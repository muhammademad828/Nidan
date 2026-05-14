<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use App\Models\City;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $governorates = Governorate::with('cities')->get();
        return view('admin.shipping.index', compact('governorates'));
    }

    public function updatePrice(Request $request, City $city)
    {
        $request->validate([
            'shipping_price' => 'required|numeric|min:0',
        ]);

        $city->update([
            'shipping_price' => $request->shipping_price,
        ]);

        return back()->with('success', 'Shipping price updated for ' . $city->name_en);
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'shipping_price' => 'required|numeric|min:0',
        ]);

        City::create($request->all());

        return back()->with('success', 'City added successfully.');
    }

    public function destroyCity(City $city)
    {
        $city->delete();
        return back()->with('success', 'City deleted.');
    }
}
