<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get();

        $grouped = $settings->groupBy('group')->map(function ($items, $group) {
            return $items->map(fn($s) => [
                'id'    => $s->id,
                'key'   => $s->key,
                'value' => $s->value,
                'type'  => $s->type,
                'label' => $s->label,
                'group' => $s->group,
            ]);
        });

        return Inertia::render('Admin/Settings/Index', [
            'groups' => $grouped,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'         => ['required', 'array'],
            'settings.*.id'    => ['required', 'exists:site_settings,id'],
            'settings.*.value' => ['nullable', 'string', 'max:10000'],
        ]);

        foreach ($request->input('settings') as $item) {
            SiteSetting::where('id', $item['id'])->update([
                'value' => $item['value'],
            ]);
        }

        $this->clearCmsCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم حفظ الإعدادات بنجاح');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
            'id'    => ['required', 'exists:site_settings,id'],
        ]);

        $file = $request->file('image');

        // Store the original image with a unique name
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;
        $path = 'settings/' . $filename;

        // Store the original file
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $setting = SiteSetting::findOrFail($request->input('id'));

        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->update(['value' => $path]);

        $this->clearCmsCache();

        return back()->with('success', 'تم رفع الصورة بنجاح');
    }

    private function clearCmsCache(): void
    {
        Cache::forget('cms:all_settings');
        $pages = ['home', 'product', 'categories', 'cart', 'checkout', 'global'];
        foreach (['en', 'ar'] as $locale) {
            foreach ($pages as $page) {
                Cache::forget("cms:sections:{$page}:{$locale}");
                Cache::forget("cms:content:{$page}:{$locale}");
                Cache::forget("cms:seo:{$page}:{$locale}");
            }
        }
    }
}
