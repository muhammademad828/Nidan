<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingController extends Controller
{
    public function index()
    {
        $governorates = Governorate::with('cities')
            ->orderBy('display_order')
            ->get()
            ->map(fn ($g) => [
                'id'       => $g->id,
                'name_ar'  => $g->name_ar,
                'name_en'  => $g->name_en,
                'is_active' => $g->is_active,
                'cities'   => $g->cities->map(fn ($c) => [
                    'id'           => $c->id,
                    'name_ar'      => $c->name_ar,
                    'name_en'      => $c->name_en,
                    'delivery_fee' => (float) $c->delivery_fee,
                    'is_active'    => $c->is_active,
                    'display_order' => $c->display_order,
                ]),
            ]);

        return Inertia::render('Admin/Shipping/Index', [
            'governorates' => $governorates,
        ]);
    }

    public function updateCity(Request $request, City $city)
    {
        $data = $request->validate([
            'delivery_fee' => ['required', 'numeric', 'min:0', 'max:9999'],
            'is_active'    => ['required', 'boolean'],
        ]);

        $city->update($data);

        return back()->with('success', app()->getLocale() === 'ar'
            ? 'تم تحديث رسوم التوصيل بنجاح'
            : 'Delivery fee updated successfully.');
    }

    public function bulkUpdateGovernorate(Request $request, Governorate $governorate)
    {
        $data = $request->validate([
            'delivery_fee' => ['required', 'numeric', 'min:0', 'max:9999'],
        ]);

        $governorate->cities()->update(['delivery_fee' => $data['delivery_fee']]);

        return back()->with('success', app()->getLocale() === 'ar'
            ? 'تم تحديث جميع رسوم المحافظة'
            : 'All cities in this governorate updated.');
    }

    public function toggleGovernorate(Request $request, Governorate $governorate)
    {
        $governorate->update(['is_active' => ! $governorate->is_active]);
        $governorate->cities()->update(['is_active' => $governorate->is_active]);

        return back()->with('success', app()->getLocale() === 'ar'
            ? 'تم تحديث حالة المحافظة'
            : 'Governorate status updated.');
    }
}
