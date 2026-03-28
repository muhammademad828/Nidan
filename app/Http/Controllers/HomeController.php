<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partner;
use App\Models\Section;
use App\Models\ServiceOffering;
use App\Services\CmsService;
use App\Services\LocationService;
use App\Services\ProductService;
use App\View\SiteStorefrontData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private LocationService $locationService,
    ) {}

    public function __invoke(Request $request): View
    {
        $region = $this->locationService->getCurrentRegion();
        $cms = app(CmsService::class);
        $shared = SiteStorefrontData::shared();

        $mapCards = fn ($collection) => $collection
            ->map(fn ($p) => $this->productService->toCardArray($p))
            ->values()
            ->all();

        $featuredProducts = $mapCards(
            $this->productService->getFeaturedProducts($region?->id, 12)
        );

        $walletsCategory = Category::active()->where('slug', 'wallets')->first()
            ?? Category::active()->where('slug', 'accessories')->first();

        $walletProducts = $walletsCategory
            ? $mapCards($this->productService->getProductsByCategorySlug($region?->id, $walletsCategory->slug, 6))
            : [];

        $jewelryProducts = $mapCards(
            $this->productService->getProductsByCategorySlug($region?->id, 'jewelry', 6)
        );

        $serviceOfferings = ServiceOffering::active()->ordered()->get();

        $partners = Partner::active()->ordered()->get();

        $sections = Section::forPage('home')->visible()->ordered()->get();

        return view('site.home', array_merge($shared, [
            'visualEditorEnabled' => (bool) ($request->user()?->is_admin),
            'featuredProducts' => $featuredProducts,
            'bestSellerProducts' => array_slice($featuredProducts, 0, 6),
            'walletProducts' => $walletProducts,
            'jewelryProducts' => $jewelryProducts,
            'serviceOfferings' => $serviceOfferings,
            'partners' => $partners,
            'sections' => $sections,
            'homeSections' => $cms->getPageSections('home'),
            'seo' => $cms->getSeoMeta('home'),
        ]));
    }
}
