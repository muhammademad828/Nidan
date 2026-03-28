<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Rules\EgyptianPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with('items', 'region')
            ->paginate(10)
            ->through(fn ($o) => [
                'id'           => $o->id,
                'order_number' => $o->order_number,
                'status'       => $o->status,
                'total'        => (float) $o->total,
                'currency'     => $o->currency,
                'items_count'  => $o->items->count(),
                'items'        => $o->items->map(fn ($i) => [
                    'product_name' => $i->product_name,
                    'product_sku'  => $i->product_sku,
                    'quantity'     => $i->quantity,
                    'unit_price'   => (float) $i->unit_price,
                    'total_price'  => (float) $i->total_price,
                ]),
                'region'       => $o->region?->name,
                'created_at'   => $o->created_at->format('Y-m-d'),
            ]);
        $orders->withQueryString();

        $addresses = $user->addresses()
            ->with('region')
            ->orderByDesc('is_default')
            ->get()
            ->map(fn ($a) => [
                'id'             => $a->id,
                'label'          => $a->label,
                'recipient_name' => $a->recipient_name,
                'phone'          => $a->phone,
                'region_id'      => $a->region_id,
                'region_name'    => $a->region?->name,
                'address_line'   => $a->address_line,
                'city'           => $a->city,
                'building'       => $a->building,
                'floor'          => $a->floor,
                'apartment'      => $a->apartment,
                'landmark'       => $a->landmark,
                'is_default'     => $a->is_default,
            ]);

        return Inertia::render('Profile/Index', [
            'orders'      => $orders,
            'addresses'   => $addresses,
            'initialTab'  => $this->normalizeProfileTab(request()->query('tab')),
            'user'        => [
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    public function addresses()
    {
        return redirect()->route('profile.index', ['tab' => 'addresses']);
    }

    private function normalizeProfileTab(?string $tab): string
    {
        $allowed = ['profile', 'orders', 'addresses'];

        return in_array($tab, $allowed, true) ? $tab : 'orders';
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', new EgyptianPhone],
            'region_id'      => ['required', 'exists:regions,id'],
            'address_line'   => ['required', 'string', 'max:500'],
            'city'           => ['nullable', 'string', 'max:100'],
            'building'       => ['nullable', 'string', 'max:100'],
            'floor'          => ['nullable', 'string', 'max:20'],
            'apartment'      => ['nullable', 'string', 'max:20'],
            'landmark'       => ['nullable', 'string', 'max:255'],
            'is_default'     => ['boolean'],
        ]);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        Auth::user()->addresses()->create($validated);

        return redirect()->route('profile.index', ['tab' => 'addresses'])->with('success', 'تم إضافة العنوان بنجاح');
    }

    public function updateAddress(Request $request, CustomerAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone'          => ['required', 'string', new EgyptianPhone],
            'region_id'      => ['required', 'exists:regions,id'],
            'address_line'   => ['required', 'string', 'max:500'],
            'city'           => ['nullable', 'string', 'max:100'],
            'building'       => ['nullable', 'string', 'max:100'],
            'floor'          => ['nullable', 'string', 'max:20'],
            'apartment'      => ['nullable', 'string', 'max:20'],
            'landmark'       => ['nullable', 'string', 'max:255'],
            'is_default'     => ['boolean'],
        ]);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return redirect()->route('profile.index', ['tab' => 'addresses'])->with('success', 'تم تحديث العنوان بنجاح');
    }

    public function destroyAddress(CustomerAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);
        $address->delete();

        return redirect()->route('profile.index', ['tab' => 'addresses'])->with('success', 'تم حذف العنوان');
    }
}
