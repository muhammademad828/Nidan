<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

/**
 * SiteSetting group `home` — editable in dashboard; HomeContentService supplies test1 fallbacks if empty.
 */
class HomeLandingSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['key' => 'announcement_text', 'value' => 'Complimentary 2-Hour Delivery on Orders Over AED 500', 'type' => 'text', 'label' => 'Home: Announcement bar'],
            ['key' => 'hero_title', 'value' => '', 'type' => 'textarea', 'label' => 'Home: Hero title (full — optional; overrides split title)'],
            ['key' => 'hero_title_before', 'value' => 'Bespoke', 'type' => 'text', 'label' => 'Home: Hero title (before accent)'],
            ['key' => 'hero_title_accent', 'value' => 'Blooms', 'type' => 'text', 'label' => 'Home: Hero accent word'],
            ['key' => 'hero_title_after', 'value' => '& Custom Creations', 'type' => 'text', 'label' => 'Home: Hero title (after accent)'],
            ['key' => 'hero_subtitle', 'value' => 'Crafting ephemeral moments into lasting memories through the art of curated floristry and artisanal design.', 'type' => 'textarea', 'label' => 'Home: Hero subtitle'],
            ['key' => 'hero_cta_upload', 'value' => 'Upload your photo', 'type' => 'text', 'label' => 'Home: Hero upload CTA'],
            ['key' => 'hero_image_alt', 'value' => 'Luxury Bouquet', 'type' => 'text', 'label' => 'Home: Hero image alt'],
            ['key' => 'services_eyebrow', 'value' => 'Our Expertise', 'type' => 'text', 'label' => 'Home: Services eyebrow'],
            ['key' => 'services_title', 'value' => 'Event Planning', 'type' => 'text', 'label' => 'Home: Services title'],
            ['key' => 'services_intro', 'value' => 'From intimate gatherings to grand celebrations, we weave botanical stories that resonate.', 'type' => 'textarea', 'label' => 'Home: Services intro'],
            ['key' => 'section_best_sellers', 'value' => 'Best Sellers', 'type' => 'text', 'label' => 'Home: Best Sellers heading'],
            ['key' => 'section_wallets', 'value' => 'Wallets', 'type' => 'text', 'label' => 'Home: Wallets heading'],
            ['key' => 'section_jewelry', 'value' => 'Jewelry', 'type' => 'text', 'label' => 'Home: Jewelry heading'],
            ['key' => 'section_partners', 'value' => 'Partners', 'type' => 'text', 'label' => 'Home: Partners heading'],
            ['key' => 'footer_brand', 'value' => 'Nidan', 'type' => 'text', 'label' => 'Home: Footer brand'],
            ['key' => 'footer_copyright', 'value' => '© 2024 Nidan Atelier. All rights reserved.', 'type' => 'text', 'label' => 'Home: Footer copyright'],
            ['key' => 'footer_link_privacy', 'value' => 'Privacy Policy', 'type' => 'text', 'label' => 'Home: Footer link — Privacy'],
            ['key' => 'footer_link_terms', 'value' => 'Terms of Service', 'type' => 'text', 'label' => 'Home: Footer link — Terms'],
            ['key' => 'footer_link_shipping', 'value' => 'Shipping & Returns', 'type' => 'text', 'label' => 'Home: Footer link — Shipping'],
            ['key' => 'products_shop_eyebrow', 'value' => 'Collection', 'type' => 'text', 'label' => 'Products page: eyebrow'],
            ['key' => 'products_shop_title', 'value' => 'Shop', 'type' => 'text', 'label' => 'Products page: title'],
            ['key' => 'products_shop_tagline', 'value' => 'Curated pieces in the same spirit as our atelier — form, texture, and quiet luxury.', 'type' => 'textarea', 'label' => 'Products page: tagline'],
            ['key' => 'products_empty_message', 'value' => 'No products match your filters yet.', 'type' => 'text', 'label' => 'Products page: empty state'],
            ['key' => 'nav_label_floral', 'value' => 'Floral', 'type' => 'text', 'label' => 'Nav: Floral'],
            ['key' => 'nav_label_services', 'value' => 'Services', 'type' => 'text', 'label' => 'Nav: Services'],
            ['key' => 'nav_label_about', 'value' => 'About', 'type' => 'text', 'label' => 'Nav: About'],
            ['key' => 'nav_label_contact', 'value' => 'Contact', 'type' => 'text', 'label' => 'Nav: Contact'],
        ];

        foreach ($rows as $data) {
            SiteSetting::updateOrCreate(
                ['key' => $data['key'], 'group' => 'home'],
                array_merge($data, ['group' => 'home'])
            );
        }
    }
}
