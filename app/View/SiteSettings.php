<?php

namespace App\View;

use App\Services\CmsService;
use App\Services\HomeContentService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Fluent;

/**
 * Cached storefront settings as a single object for Blade ($siteSettings->key).
 * Built from SiteSetting rows + HomeContentService fallbacks.
 */
final class SiteSettings
{
    public const CACHE_KEY = 'nidan:site_settings_bag';

    private static ?Fluent $requestMemo = null;

    public static function flushRequestMemo(): void
    {
        self::$requestMemo = null;
    }

    public static function resolveCached(): Fluent
    {
        if (self::$requestMemo instanceof Fluent) {
            return self::$requestMemo;
        }

        return self::$requestMemo = Cache::remember(self::CACHE_KEY, 86400, fn () => self::build());
    }

    public static function build(): Fluent
    {
        $cms = app(CmsService::class);
        $grouped = $cms->getAllSettingsGrouped();
        $resolvedHome = app(HomeContentService::class)->resolve($grouped);

        $flat = [];

        foreach (['general', 'branding', 'contact', 'social', 'delivery', 'payment', 'home', 'layout'] as $group) {
            foreach ($grouped[$group] ?? [] as $key => $value) {
                $flat[$group.'_'.$key] = $value;
                $flat[$key] = $value;
            }
        }

        $savedFullHeroTitle = isset($flat['hero_title']) ? trim((string) $flat['hero_title']) : '';

        foreach ($resolvedHome as $key => $value) {
            $flat[$key] = $value;
        }

        $flat['hero_title_is_custom'] = $savedFullHeroTitle !== '';

        if ($savedFullHeroTitle !== '') {
            $flat['hero_title'] = $savedFullHeroTitle;
        } elseif (empty(trim((string) ($flat['hero_title'] ?? '')))) {
            $flat['hero_title'] = trim(
                ($flat['hero_title_before'] ?? '').' '
                .($flat['hero_title_accent'] ?? '').' '
                .($flat['hero_title_after'] ?? '')
            );
        }

        $flat['logo_url'] = self::publicAssetUrl($flat['logo'] ?? null);
        $flat['logo_dark_url'] = self::publicAssetUrl($flat['logo_dark'] ?? null);
        $flat['favicon_url'] = self::publicAssetUrl($flat['favicon'] ?? null);

        $flat['primary_color'] = self::sanitizeHexColor($flat['primary_color'] ?? null, '#745b29');
        $flat['secondary_color'] = self::sanitizeHexColor($flat['secondary_color'] ?? null, '#F9F5F0');
        $flat['site_name'] = $flat['site_name'] ?? config('app.name', 'Nidan');
        $flat['default_currency'] = $flat['default_currency'] ?? 'EGP';

        $flat['products_shop_eyebrow'] = $flat['products_shop_eyebrow'] ?? 'Collection';
        $flat['products_shop_title'] = $flat['products_shop_title'] ?? 'Shop';
        $flat['products_shop_tagline'] = $flat['products_shop_tagline'] ?? 'Curated pieces in the same spirit as our atelier — form, texture, and quiet luxury.';
        $flat['products_empty_message'] = $flat['products_empty_message'] ?? 'No products match your filters yet.';

        $flat['nav_label_floral'] = $flat['nav_label_floral'] ?? 'Floral';
        $flat['nav_label_services'] = $flat['nav_label_services'] ?? 'Services';
        $flat['nav_label_about'] = $flat['nav_label_about'] ?? 'About';
        $flat['nav_label_contact'] = $flat['nav_label_contact'] ?? 'Contact';

        return new Fluent($flat);
    }

    private static function sanitizeHexColor(?string $value, string $fallback): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }
        $v = trim($value);

        return preg_match('/^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/', $v) ? $v : $fallback;
    }

    private static function publicAssetUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
