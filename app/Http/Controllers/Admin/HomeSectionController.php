<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::with('tag')->orderBy('sort_order')->get();
        return view('admin.home_sections.index', compact('sections'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('admin.home_sections.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'tag_id' => 'nullable|exists:tags,id|required_if:type,collection',
            'sort_order' => 'integer',
        ]);

        HomeSection::create($request->all());

        return redirect()->route('admin.home-sections.index')->with('success', 'Home section created successfully.');
    }

    public function edit(HomeSection $homeSection)
    {
        $tags = Tag::all();
        return view('admin.home_sections.edit', compact('homeSection', 'tags'));
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'tag_id' => 'nullable|exists:tags,id|required_if:type,collection',
            'sort_order' => 'integer',
        ]);

        $homeSection->update($request->all());

        return redirect()->route('admin.home-sections.index')->with('success', 'Home section updated successfully.');
    }

    public function destroy(HomeSection $homeSection)
    {
        $homeSection->delete();
        return redirect()->route('admin.home-sections.index')->with('success', 'Home section deleted successfully.');
    }

    public function toggle(HomeSection $homeSection)
    {
        $homeSection->is_active = !$homeSection->is_active;
        $homeSection->save();
        return back()->with('success', 'Visibility toggled.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:home_sections,id'
        ]);

        foreach ($request->order as $index => $id) {
            HomeSection::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
