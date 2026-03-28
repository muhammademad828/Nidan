<?php

namespace App\Services;

use App\Models\ContentBlock;
use App\Models\PageSection;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\View\SiteSettings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CmsService
{
    /**
     * Forget all CMS forever-cache keys (all locales) so dashboard edits show immediately.
     */
    public static function invalidateForeverCaches(): void
    {
        Cache::forget('cms:all_settings');
        Cache::forget(SiteSettings::CACHE_KEY);
        SiteSettings::flushRequestMemo();

        $locales = array_unique(array_filter([
            config('app.locale'),
            config('app.fallback_locale'),
            'en',
            'ar',
        ]));

        $sectionPages = ['home', 'global', 'product', 'products', 'checkout', 'cart', 'categories'];
        $contentPages = ['home', 'global', 'product', 'products', 'checkout', 'cart', 'categories'];
        $seoPages = ['home', 'products', 'checkout', 'product'];

        foreach ($locales as $locale) {
            foreach ($sectionPages as $page) {
                Cache::forget("cms:sections:{$page}:{$locale}");
            }
            foreach ($contentPages as $page) {
                Cache::forget("cms:content:{$page}:{$locale}");
            }
            foreach ($seoPages as $page) {
                Cache::forget("cms:seo:{$page}:{$locale}");
            }
        }
    }

    public function getAllSettingsGrouped(): array
    {
        return Cache::rememberForever('cms:all_settings', function () {
            return SiteSetting::all()
                ->groupBy('group')
                ->map(fn ($items) => $items->pluck('value', 'key'))
                ->toArray();
        });
    }

    public function getSetting(string $group, string $key, mixed $default = null): mixed
    {
        return $this->getAllSettingsGrouped()[$group][$key] ?? $default;
    }

    public function getPageSections(string $page): array
    {
        $locale = App::getLocale();

        return Cache::rememberForever("cms:sections:{$page}:{$locale}", function () use ($page, $locale) {
            $sections = PageSection::where('page', $page)
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get();

            $result = [];
            foreach ($sections as $section) {
                $result[$section->section_key] = [
                    'title'            => $locale === 'ar' ? $section->title_ar : $section->title_en,
                    'subtitle'         => $locale === 'ar' ? $section->subtitle_ar : $section->subtitle_en,
                    'description'      => $locale === 'ar' ? $section->description_ar : $section->description_en,
                    'button_text'      => $locale === 'ar' ? $section->button_text_ar : $section->button_text_en,
                    'button_url'       => $section->button_url,
                    'background_image' => $section->background_image
                        ? (str_starts_with($section->background_image, 'http')
                            ? $section->background_image
                            : asset('storage/' . $section->background_image))
                        : null,
                    'extra_data'       => $section->extra_data,
                ];
            }

            return $result;
        });
    }

    public function getContentBlocks(string $page): array
    {
        $locale = App::getLocale();

        return Cache::rememberForever("cms:content:{$page}:{$locale}", function () use ($page, $locale) {
            return ContentBlock::where('page', $page)
                ->get()
                ->pluck($locale === 'ar' ? 'value_ar' : 'value_en', 'key')
                ->map(fn ($v, $k) => $v ?: ContentBlock::where('key', $k)->value('value_en'))
                ->toArray();
        });
    }

    public function getGlobalContent(): array
    {
        return $this->getContentBlocks('global');
    }

    public function getSeoMeta(string $page): array
    {
        $locale = App::getLocale();

        return Cache::rememberForever("cms:seo:{$page}:{$locale}", function () use ($page, $locale) {
            $meta = SeoMeta::where('page', $page)->first();
            if (! $meta) return [];

            return [
                'meta_title'       => $locale === 'ar' ? $meta->meta_title_ar : $meta->meta_title_en,
                'meta_description' => $locale === 'ar' ? $meta->meta_description_ar : $meta->meta_description_en,
                'og_title'         => $locale === 'ar' ? ($meta->og_title_ar ?: $meta->meta_title_ar) : ($meta->og_title_en ?: $meta->meta_title_en),
                'og_description'   => $locale === 'ar' ? ($meta->og_description_ar ?: $meta->meta_description_ar) : ($meta->og_description_en ?: $meta->meta_description_en),
                'og_image'         => $meta->og_image,
            ];
        });
    }

    public function getPageKey(string $routeName): string
    {
        return match(true) {
            str_starts_with($routeName, 'home')        => 'home',
            str_starts_with($routeName, 'products')    => 'product',
            str_starts_with($routeName, 'categories')  => 'categories',
            str_starts_with($routeName, 'cart')        => 'cart',
            str_starts_with($routeName, 'checkout')    => 'checkout',
            default => 'global',
        };
    }
}
