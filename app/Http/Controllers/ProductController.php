<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\CmsService;
use App\Services\LocationService;
use App\Services\ProductService;
use App\View\SiteStorefrontData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private LocationService $locationService,
    ) {}

    public function index(Request $request): View
    {
        $region = $this->locationService->getCurrentRegion();

        $products = $this->productService->getFilteredProducts(
            category:   $request->input('category'),
            occasion:   $request->input('occasion'),
            priceRange: $request->input('price'),
            sort:       $request->input('sort', 'featured'),
            regionId:   $region?->id,
            search:     $request->input('q'),
        );

        $cardRows = $products->through(fn ($p) => $this->productService->toCardArray($p));

        return view('site.products.index', array_merge(SiteStorefrontData::shared(), [
            'products' => $cardRows,
            'categories' => Category::active()->ordered()->get(),
            'filters' => $request->only(['category', 'occasion', 'price', 'sort', 'q']),
            'seo' => app(CmsService::class)->getSeoMeta('products'),
            'visualEditorEnabled' => (bool) ($request->user()?->is_admin),
        ]));
    }

    public function show(string $slug): Response
    {
        $product = $this->productService->getProductBySlug($slug);
        $product->loadMissing(['images', 'variations', 'addons', 'category']);

        $locale = App::getLocale();
        $nameCol = $locale === 'ar' ? 'name_ar' : 'name_en';
        $shortCol = $locale === 'ar' ? 'short_description_ar' : 'short_description_en';
        $descCol = $locale === 'ar' ? 'description_ar' : 'description_en';
        $descRaw = (string) ($product->getRawOriginal($descCol) ?? '');
        $descriptionPlain = html_entity_decode(strip_tags($descRaw), ENT_QUOTES, 'UTF-8');

        return Inertia::render('Products/Show', [
            'product' => [
                'id'                => $product->id,
                'name'              => $product->name,
                'slug'              => $product->slug,
                'sku'               => $product->sku,
                'description'       => $product->description,
                'description_plain' => $descriptionPlain,
                'short_description' => $product->short_description,
                'editable_name_column' => $nameCol,
                'editable_short_column' => $shortCol,
                'editable_description_column' => $descCol,
                'base_price'        => (float) $product->base_price,
                'compare_at_price'  => $product->compare_at_price ? (float) $product->compare_at_price : null,
                'stock_status'      => $product->stock_status,
                'stock_quantity'    => $product->stock_quantity,
                'is_giftable'       => $product->is_giftable,
                'requires_delivery_slot' => $product->requires_delivery_slot,
                'has_delivery_time' => (bool) $product->has_delivery_time,
                'category'          => $product->category?->name,
                /** Full URLs — raw DB paths break under /products/{slug} relative resolution */
                'primary_image'     => $product->primary_image,
                'images'            => $this->galleryForProductPage($product),
                'variations'        => $product->variations->map(fn ($v) => [
                    'id'             => $v->id,
                    'name'           => $v->name,
                    'type'           => $v->type,
                    'value'          => $v->value,
                    'price_modifier' => (float) $v->price_modifier,
                    'stock_quantity' => $v->stock_quantity,
                ]),
                'addons'            => $product->addons->map(fn ($a) => [
                    'id'          => $a->id,
                    'name'        => $a->name,
                    'description' => $a->description,
                    'price'       => (float) $a->price,
                ]),
            ],
        ]);
    }

    /**
     * Gallery images as absolute URLs. Falls back to primary_image_path when no gallery exists.
     */
    private function galleryForProductPage(Product $product): array
    {
        $gallery = [];
        if (!empty($product->gallery) && is_array($product->gallery)) {
            foreach ($product->gallery as $index => $path) {
                $url = Product::publicImageUrl($path);
                if ($url) {
                    $gallery[] = [
                        'path'       => $url,
                        'alt_text'   => $product->name,
                        'is_primary' => $index === 0,
                    ];
                }
            }
        }

        if (count($gallery) > 0) {
            return $gallery;
        }

        $fallback = $product->primary_image ?: Product::publicImageUrl($product->primary_image_path);
        if ($fallback) {
            return [[
                'path'       => $fallback,
                'alt_text'   => $product->name,
                'is_primary' => true,
            ]];
        }

        // Keep a stable array shape to avoid frontend runtime issues when gallery is empty.
        return [[
            'path'       => null,
            'alt_text'   => $product->name,
            'is_primary' => true,
        ]];
    }
}
