<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('sort_order')->orderBy('id', 'desc')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(UpsertCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name_en']);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpsertCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        if (!isset($validated['name_en'])) {
            $validated['name_en'] = $category->name_en;
        }

        $validated['slug'] = ($validated['slug'] ?? null) ?: Str::slug($validated['name_en']);

        if ($request->hasFile('image')) {
            if ($category->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

}
