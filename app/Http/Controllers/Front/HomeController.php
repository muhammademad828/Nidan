<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Review;
use App\Services\BilingualService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $bestSellers = Product::active()
            ->with('category')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $testimonials = Review::approved()
            ->with('product')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'name'    => $r->name,
                'comment' => $r->comment,
                'product' => $r->product?->name,
                'rating'  => $r->rating,
            ])
            ->toArray();

        $slides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();

        $homeSections = \App\Models\HomeSection::where('is_active', true)
            ->with(['tag.products' => function($q) {
                $q->active()->limit(4);
            }])
            ->orderBy('sort_order')
            ->get();

        $eventSlides = \App\Models\EventSlide::where('is_active', true)->orderBy('sort_order')->get();

        return view('front.home.index', compact('bestSellers', 'testimonials', 'slides', 'homeSections', 'eventSlides'));
    }

    public function switchLang(string $targetLocale): RedirectResponse
    {
        if (!in_array($targetLocale, config('bilingual.locales'))) {
            $targetLocale = config('bilingual.default');
        }

        $url = BilingualService::localeUrl($targetLocale, request()->path());
        return redirect($url);
    }

    public function testDebug()
    {
        return App\Models\HeroSlide::all()->map(function($s) {
            return [
                'id' => $s->id,
                'media_type' => $s->media_type,
                'media_url' => $s->media_url,
                'image' => $s->image,
                'media_attr' => $s->media
            ];
        });
    }
}
