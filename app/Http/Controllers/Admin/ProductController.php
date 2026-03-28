<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->input('search'), function ($q, $s) {
                $q->where(fn ($q2) => $q2
                    ->where('name_en', 'like', "%{$s}%")
                    ->orWhere('name_ar', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%")
                );
            })
            ->when($request->input('category'), fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($p) => [
                'id'            => $p->id,
                'name_en'       => $p->name_en,
                'name_ar'       => $p->name_ar,
                'sku'           => $p->sku,
                'base_price'    => (float) $p->base_price,
                'stock_status'  => $p->stock_status,
                'stock_quantity' => $p->stock_quantity,
                'is_active'     => $p->is_active,
                'is_featured'   => $p->is_featured,
                'category'      => $p->category?->name_en,
                'image'         => $p->primary_image,
                'updated_at'    => $p->updated_at->format('Y-m-d'),
            ]);

        $categories = Category::orderBy('name_en')->get()->map(fn ($c) => [
            'id'   => $c->id,
            'name' => $c->name_en,
        ]);

        return Inertia::render('Admin/Products/Index', [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $request->only(['search', 'category']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Form', [
            'product'    => null,
            'categories' => Category::orderBy('name_en')->get()->map(fn ($c) => [
                'id' => $c->id, 'name' => $c->name_en,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        $validated['slug'] = Str::slug($validated['name_en']) . '-' . Str::random(4);

        $product = Product::create($validated);
        $this->persistUploadedGalleryImages($request, $product, false);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Form', [
            'product' => [
                'id'                   => $product->id,
                'name_en'              => $product->name_en,
                'name_ar'              => $product->name_ar,
                'sku'                  => $product->sku,
                'category_id'          => $product->category_id,
                'base_price'           => (float) $product->base_price,
                'compare_at_price'     => $product->compare_at_price ? (float) $product->compare_at_price : null,
                'short_description_en' => $product->short_description_en,
                'short_description_ar' => $product->short_description_ar,
                'description_en'       => $product->description_en,
                'description_ar'       => $product->description_ar,
                'stock_status'         => $product->stock_status,
                'stock_quantity'       => $product->stock_quantity,
                'is_active'            => $product->is_active,
                'is_featured'          => $product->is_featured,
                'is_giftable'          => $product->is_giftable,
                'requires_delivery_slot' => (bool) $product->requires_delivery_slot,
                'has_delivery_time'    => (bool) $product->has_delivery_time,
                'image'                => $product->primary_image,
                'gallery'              => collect($product->gallery ?? [])->map(fn ($path) => [
                    'path' => $path,
                    'url'  => Product::publicImageUrl($path),
                ])->values()->all(),
            ],
            'categories' => Category::orderBy('name_en')->get()->map(fn ($c) => [
                'id' => $c->id, 'name' => $c->name_en,
            ]),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        $product->update($validated);
        $this->persistUploadedGalleryImages($request, $product, true);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'تم حذف المنتج');
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name_en'              => ['required', 'string', 'max:255'],
            'name_ar'              => ['required', 'string', 'max:255'],
            'sku'                  => ['required', 'string', 'max:100', 'unique:products,sku' . ($ignoreId ? ",{$ignoreId}" : '')],
            'category_id'          => ['required', 'exists:categories,id'],
            'base_price'           => ['required', 'numeric', 'min:0'],
            'compare_at_price'     => ['nullable', 'numeric', 'min:0'],
            'short_description_en' => ['nullable', 'string', 'max:500'],
            'short_description_ar' => ['nullable', 'string', 'max:500'],
            'description_en'       => ['nullable', 'string'],
            'description_ar'       => ['nullable', 'string'],
            'stock_status'         => ['required', 'in:in_stock,low_stock,out_of_stock'],
            'stock_quantity'       => ['nullable', 'integer', 'min:0'],
            'is_active'            => ['boolean'],
            'is_featured'          => ['boolean'],
            'is_giftable'          => ['boolean'],
            'requires_delivery_slot' => ['boolean'],
            'has_delivery_time'    => ['boolean'],
            'gallery_existing'     => ['nullable', 'array'],
            'gallery_existing.*'   => ['string'],
            'gallery_new'          => ['nullable', 'array'],
            'gallery_new.*'        => ['image', 'max:5120'],
        ], [
            'gallery_new.*.image' => app()->getLocale() === 'ar' 
                ? 'يجب أن تكون الملفات المرفوعة في المعرض صوراً صالحة.' 
                : 'Gallery new files must be images.',
            'gallery_new.*.max'   => app()->getLocale() === 'ar' 
                ? 'حجم الصورة في المعرض يجب ألا يتجاوز 5 ميجابايت.' 
                : 'Gallery new must not be greater than 5MB.',
        ]);
    }

    private function persistUploadedGalleryImages(Request $request, Product $product, bool $isUpdate): void
    {
        $gallery = $request->input('gallery_existing', []);
        
        $uploadedImages = $request->file('gallery_new', []);
        if (is_array($uploadedImages)) {
            foreach ($uploadedImages as $file) {
                $path = $file->store('products', 'public');
                $gallery[] = $path;
            }
        }

        $product->update(['gallery' => empty($gallery) ? null : $gallery]);

        if (count($gallery) > 0 && !$product->primary_image_path) {
            $product->update(['primary_image_path' => $gallery[0]]);
        }
    }
}
