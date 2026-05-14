<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.value_en' => 'nullable|string',
            'settings.*.value_ar' => 'nullable|string',
        ]);

        foreach ($data['settings'] as $id => $values) {
            $setting = SiteSetting::findOrFail($id);
            $setting->update($values);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function announcement()
    {
        $setting = SiteSetting::where('key', 'announcement_text')->first();
        return view('admin.settings.announcement', compact('setting'));
    }

    public function updateAnnouncement(Request $request)
    {
        $request->validate([
            'value_en' => 'required|string',
            'value_ar' => 'required|string',
        ]);

        $setting = SiteSetting::where('key', 'announcement_text')->first();
        if ($setting) {
            $setting->update([
                'value_en' => $request->value_en,
                'value_ar' => $request->value_ar,
            ]);
        } else {
            SiteSetting::create([
                'key' => 'announcement_text',
                'value_en' => $request->value_en,
                'value_ar' => $request->value_ar,
                'group' => 'general',
            ]);
        }

        return redirect()->back()->with('success', 'Announcement bar updated successfully.');
    }
}
