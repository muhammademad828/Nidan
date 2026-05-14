<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertAddOnRequest;
use App\Models\AddOn;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddOnController extends Controller
{
    public function index(): View
    {
        $addons = AddOn::orderBy('sort_order')->orderBy('id', 'desc')->paginate(20);
        return view('admin.addons.index', compact('addons'));
    }

    public function store(UpsertAddOnRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('addons', 'public');
        }
        AddOn::create($data);
        return back()->with('success', 'Add-on created.');
    }

    public function update(UpsertAddOnRequest $request, AddOn $addon): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($addon->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($addon->image);
            }
            $data['image'] = $request->file('image')->store('addons', 'public');
        }
        $addon->update($data);
        return back()->with('success', 'Add-on updated.');
    }

    public function destroy(AddOn $addon): RedirectResponse
    {
        $addon->delete();
        return back()->with('success', 'Add-on deleted.');
    }
}
