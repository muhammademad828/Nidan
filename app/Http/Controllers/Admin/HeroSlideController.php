<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->get();
        return view('admin.slides.index', compact('slides'));
    }

    public function create()
    {
        $availableRoutes = $this->getAvailableRoutes();
        return view('admin.slides.create', compact('availableRoutes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:1000',
            'subtitle_ar' => 'nullable|string|max:1000',
            'media_type' => 'required|in:image,video',
            'media_url' => 'nullable|string|required_without:media_file',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,wmv|max:20480|required_without:media_url',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'secondary_button_text_en' => 'nullable|string|max:100',
            'secondary_button_text_ar' => 'nullable|string|max:100',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('media_file')) {
            $path = $request->file('media_file')->store('slides', 'public');
            $data['image'] = $path;
            $data['media_url'] = '/storage/' . $path;
        }

        $data['is_active'] = $request->has('is_active');

        HeroSlide::create($data);

        return redirect()->route('admin.slides.index')->with('success', 'Slide created successfully.');
    }

    public function edit(HeroSlide $slide)
    {
        $availableRoutes = $this->getAvailableRoutes();
        return view('admin.slides.edit', compact('slide', 'availableRoutes'));
    }

    public function update(Request $request, HeroSlide $slide)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:1000',
            'subtitle_ar' => 'nullable|string|max:1000',
            'media_type' => 'required|in:image,video',
            'media_url' => 'nullable|string',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,wmv|max:20480',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'secondary_button_text_en' => 'nullable|string|max:100',
            'secondary_button_text_ar' => 'nullable|string|max:100',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('media_file')) {
            if ($slide->image && Storage::disk('public')->exists($slide->image)) {
                Storage::disk('public')->delete($slide->image);
            }
            $path = $request->file('media_file')->store('slides', 'public');
            $data['image'] = $path;
            $data['media_url'] = '/storage/' . $path;
        }

        $data['is_active'] = $request->has('is_active');

        $slide->update($data);

        return redirect()->route('admin.slides.index')->with('success', 'Slide updated successfully.');
    }

    private function getAvailableRoutes()
    {
        $routes = [
            '/' => 'Home Page',
            '/collections' => 'All Collections',
            '/track' => 'Track Order',
            '/cart' => 'Shopping Cart',
            '/checkout' => 'Checkout Page',
            '/login' => 'Login Page',
            '/register' => 'Register Page',
            '/account' => 'My Account',
        ];

        // Add Categories
        try {
            $categories = \App\Models\Category::where('is_active', true)->get();
            foreach ($categories as $cat) {
                $routes['/collections/' . $cat->slug] = 'Collection: ' . $cat->name_en;
            }
        } catch (\Exception $e) {
            // Fallback if table doesn't exist or other error
        }

        // Add policies
        $routes['/policies/privacy'] = 'Privacy Policy';
        $routes['/policies/terms'] = 'Terms of Service';
        $routes['/policies/shipping'] = 'Shipping Policy';
        $routes['/policies/refund'] = 'Refund Policy';

        return $routes;
    }

    public function destroy(HeroSlide $slide)
    {
        if ($slide->image && Storage::disk('public')->exists($slide->image)) {
            Storage::disk('public')->delete($slide->image);
        }
        $slide->delete();

        return redirect()->route('admin.slides.index')->with('success', 'Slide deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:hero_slides,id'
        ]);

        foreach ($request->order as $index => $id) {
            HeroSlide::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
