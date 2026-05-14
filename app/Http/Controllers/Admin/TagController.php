<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|unique:tags,name_en',
            'name_ar' => 'required|unique:tags,name_ar',
        ]);

        Tag::create([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'slug' => Str::slug($request->name_en),
        ]);

        return back()->with('success', 'Tag created successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return back()->with('success', 'Tag deleted successfully.');
    }
}
