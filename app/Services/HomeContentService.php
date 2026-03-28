<?php

namespace App\Services;

/**
 * Resolves home landing copy from SiteSetting group `home`, with test1.html fallbacks.
 */
class HomeContentService
{
    /**
     * @param  array<string, array<string, mixed>>  $settingsGrouped  Output of CmsService::getAllSettingsGrouped()
     * @return array<string, string>
     */
    public function resolve(array $settingsGrouped): array
    {
        $h = $settingsGrouped['home'] ?? [];

        $heroImage = $this->str($h, 'hero_image');
        if ($heroImage !== '' && ! str_starts_with($heroImage, 'http')) {
            $heroImage = asset('storage/' . ltrim($heroImage, '/'));
        }

        $blob1 = $this->str($h, 'hero_blob_1');
        if ($blob1 !== '' && ! str_starts_with($blob1, 'http')) {
            $blob1 = asset('storage/' . ltrim($blob1, '/'));
        }
        $blob2 = $this->str($h, 'hero_blob_2');
        if ($blob2 !== '' && ! str_starts_with($blob2, 'http')) {
            $blob2 = asset('storage/' . ltrim($blob2, '/'));
        }

        return [
            'announcement_text' => $this->str($h, 'announcement_text', 'Complimentary 2-Hour Delivery on Orders Over AED 500'),

            'hero_title_before' => $this->str($h, 'hero_title_before', 'Bespoke'),
            'hero_title_accent' => $this->str($h, 'hero_title_accent', 'Blooms'),
            'hero_title_after' => $this->str($h, 'hero_title_after', '& Custom Creations'),
            'hero_subtitle' => $this->str($h, 'hero_subtitle', 'Crafting ephemeral moments into lasting memories through the art of curated floristry and artisanal design.'),
            'hero_cta_upload' => $this->str($h, 'hero_cta_upload', 'Upload your photo'),
            'hero_image' => $heroImage !== '' ? $heroImage : asset('images/hero-bouquet.jpg'),
            'hero_image_alt' => $this->str($h, 'hero_image_alt', 'Luxury Bouquet'),
            'hero_blob_1' => $blob1,
            'hero_blob_2' => $blob2,

            'services_eyebrow' => $this->str($h, 'services_eyebrow', 'Our Expertise'),
            'services_title' => $this->str($h, 'services_title', 'Event Planning'),
            'services_intro' => $this->str($h, 'services_intro', 'From intimate gatherings to grand celebrations, we weave botanical stories that resonate.'),

            'section_best_sellers' => $this->str($h, 'section_best_sellers', 'Best Sellers'),
            'section_wallets' => $this->str($h, 'section_wallets', 'Wallets'),
            'section_jewelry' => $this->str($h, 'section_jewelry', 'Jewelry'),
            'section_partners' => $this->str($h, 'section_partners', 'Partners'),

            'empty_best_sellers' => $this->str($h, 'empty_best_sellers', 'Our best sellers will appear here once products are marked featured in the dashboard.'),
            'empty_wallets' => $this->str($h, 'empty_wallets', 'Wallet designs will show here for the accessories category (or a wallets category when added).'),
            'empty_jewelry' => $this->str($h, 'empty_jewelry', 'Jewelry pieces will appear here from the jewelry category.'),

            'footer_brand' => $this->str($h, 'footer_brand', 'Nidan'),
            'footer_copyright' => $this->str($h, 'footer_copyright', '© 2024 Nidan Atelier. All rights reserved.'),
            'footer_link_privacy' => $this->str($h, 'footer_link_privacy', 'Privacy Policy'),
            'footer_link_terms' => $this->str($h, 'footer_link_terms', 'Terms of Service'),
            'footer_link_shipping' => $this->str($h, 'footer_link_shipping', 'Shipping & Returns'),
        ];
    }

    private function str(array $group, string $key, ?string $default = null): string
    {
        $v = $group[$key] ?? null;
        if ($v === null || $v === '') {
            return $default ?? '';
        }

        return is_string($v) ? $v : (string) $v;
    }
}
