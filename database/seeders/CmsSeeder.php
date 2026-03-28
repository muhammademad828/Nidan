<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use App\Models\PageSection;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedPageSections();
        $this->seedContentBlocks();
        $this->seedSeoMeta();
    }

    private function seedSettings(): void
    {
        $settings = [
            // Branding
            ['key' => 'logo',                  'value' => null,                        'group' => 'branding', 'type' => 'image',   'label' => 'Site Logo'],
            ['key' => 'logo_dark',             'value' => null,                        'group' => 'branding', 'type' => 'image',   'label' => 'Logo (Dark BG)'],
            ['key' => 'favicon',               'value' => null,                        'group' => 'branding', 'type' => 'image',   'label' => 'Favicon'],
            ['key' => 'primary_color',         'value' => '#C9A84C',                   'group' => 'branding', 'type' => 'text',    'label' => 'Primary Color (Gold)'],
            ['key' => 'secondary_color',       'value' => '#F9F5F0',                   'group' => 'branding', 'type' => 'text',    'label' => 'Secondary Color (Cream)'],
            ['key' => 'accent_color',          'value' => '#1A1A1A',                   'group' => 'branding', 'type' => 'text',    'label' => 'Accent Color (Dark)'],
            // General
            ['key' => 'site_name',             'value' => 'NIDAN',                     'group' => 'general',  'type' => 'text',    'label' => 'Site Name'],
            ['key' => 'site_tagline_en',       'value' => 'Made to Be Felt',           'group' => 'general',  'type' => 'text',    'label' => 'Tagline (EN)'],
            ['key' => 'site_tagline_ar',       'value' => 'صُنع ليُحَس',              'group' => 'general',  'type' => 'text',    'label' => 'Tagline (AR)'],
            ['key' => 'default_currency',      'value' => 'EGP',                       'group' => 'general',  'type' => 'text',    'label' => 'Default Currency'],
            ['key' => 'tax_rate',              'value' => '0',                          'group' => 'general',  'type' => 'text',    'label' => 'Tax Rate (%)'],
            ['key' => 'maintenance_mode',      'value' => '0',                          'group' => 'general',  'type' => 'boolean', 'label' => 'Maintenance Mode'],
            // Contact
            ['key' => 'phone_number',          'value' => '+20 100 000 0000',           'group' => 'contact',  'type' => 'text',    'label' => 'Phone Number'],
            ['key' => 'whatsapp_number',       'value' => '+201000000000',              'group' => 'contact',  'type' => 'text',    'label' => 'WhatsApp Number'],
            ['key' => 'email_address',         'value' => 'hello@nidan.com',            'group' => 'contact',  'type' => 'text',    'label' => 'Email Address'],
            ['key' => 'address_en',            'value' => 'Cairo, Egypt',               'group' => 'contact',  'type' => 'text',    'label' => 'Address (EN)'],
            ['key' => 'address_ar',            'value' => 'القاهرة، مصر',              'group' => 'contact',  'type' => 'text',    'label' => 'Address (AR)'],
            // Social
            ['key' => 'instagram_url',         'value' => 'https://instagram.com/nidangifts', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram URL'],
            ['key' => 'tiktok_url',            'value' => 'https://tiktok.com/@nidangifts',   'group' => 'social', 'type' => 'text', 'label' => 'TikTok URL'],
            ['key' => 'facebook_url',          'value' => 'https://facebook.com/nidangifts',  'group' => 'social', 'type' => 'text', 'label' => 'Facebook URL'],
            // Delivery
            ['key' => 'base_delivery_fee',     'value' => '50',                         'group' => 'delivery', 'type' => 'text',    'label' => 'Base Delivery Fee'],
            ['key' => 'free_delivery_threshold', 'value' => '2000',                    'group' => 'delivery', 'type' => 'text',    'label' => 'Free Delivery Threshold'],
            // Payment
            ['key' => 'cod_enabled',           'value' => '1',                          'group' => 'payment',  'type' => 'boolean', 'label' => 'Cash on Delivery Enabled'],
        ];

        foreach ($settings as $data) {
            SiteSetting::updateOrCreate(['key' => $data['key']], $data);
        }
    }

    private function seedPageSections(): void
    {
        $sections = [
            [
                'page' => 'home', 'section_key' => 'hero', 'display_order' => 1,
                'title_en' => 'Made to Be Felt', 'title_ar' => 'صُنع ليُحَس',
                'subtitle_en' => 'Personalized gifts, timeless pieces, and moments that last forever.',
                'subtitle_ar' => 'هدايا مخصصة، قطع خالدة، ولحظات تدوم للأبد.',
                'button_text_en' => 'Shop Now', 'button_text_ar' => 'تسوق الآن',
                'button_url' => '/products',
            ],
            [
                'page' => 'home', 'section_key' => 'discover', 'display_order' => 2,
                'title_en' => 'Discover the Essence', 'title_ar' => 'اكتشف الجوهر',
                'subtitle_en' => 'Choose your path to the perfect expression of emotion.',
                'subtitle_ar' => 'اختر مسارك نحو التعبير المثالي عن المشاعر.',
            ],
            [
                'page' => 'home', 'section_key' => 'categories', 'display_order' => 3,
                'title_en' => 'Shop by Category', 'title_ar' => 'تسوق حسب الفئة',
                'subtitle_en' => 'Curated Collections', 'subtitle_ar' => 'مجموعات منتقاة بعناية',
            ],
            [
                'page' => 'home', 'section_key' => 'featured', 'display_order' => 4,
                'title_en' => 'Featured Pieces', 'title_ar' => 'القطع المميزة',
                'subtitle_en' => 'Handpicked items for your special moments.',
                'subtitle_ar' => 'منتجات مختارة بعناية للحظاتك الخاصة.',
            ],
            [
                'page' => 'home', 'section_key' => 'heritage', 'display_order' => 5,
                'title_en' => 'Inspired by Ancient Egypt', 'title_ar' => 'مستوحاة من مصر القديمة',
                'subtitle_en' => 'Heritage Collection', 'subtitle_ar' => 'مجموعة التراث',
                'description_en' => 'Discover pieces that echo the majesty of the pharaohs — a blend of historical significance and modern luxury.',
                'description_ar' => 'اكتشف قطعاً تعكس عظمة الفراعنة — مزيج من الأهمية التاريخية والفخامة المعاصرة.',
                'button_text_en' => 'Explore the Legacy', 'button_text_ar' => 'استكشف الإرث',
                'button_url' => '/categories/antiques',
                'background_image' => 'https://images.unsplash.com/photo-1577741314755-048d8525d31e?w=1600&q=80',
                'extra_data' => json_encode([
                    ['stat_en' => '3,000+', 'label_en' => 'Years of History', 'stat_ar' => '+3,000', 'label_ar' => 'عام من التاريخ'],
                    ['stat_en' => '48',     'label_en' => 'Unique Pieces',    'stat_ar' => '48',     'label_ar' => 'قطعة فريدة'],
                    ['stat_en' => '18k',    'label_en' => 'Gold Crafted',     'stat_ar' => '18 قيراط', 'label_ar' => 'ذهب خالص'],
                ]),
            ],
            [
                'page' => 'home', 'section_key' => 'gifts', 'display_order' => 6,
                'title_en' => 'Gifts for Every Moment', 'title_ar' => 'هدايا لكل مناسبة',
            ],
            [
                'page' => 'home', 'section_key' => 'instagram', 'display_order' => 7,
                'title_en' => '@NidanGifts', 'title_ar' => '@NidanGifts',
                'subtitle_en' => 'Follow us for daily inspiration and stories.',
                'subtitle_ar' => 'تابعنا للإلهام اليومي والقصص.',
            ],
        ];

        foreach ($sections as $data) {
            PageSection::updateOrCreate(
                ['page' => $data['page'], 'section_key' => $data['section_key']],
                $data
            );
        }
    }

    private function seedContentBlocks(): void
    {
        $blocks = [
            // Global Navigation
            ['page' => 'global', 'key' => 'nav.shop',               'value_en' => 'Shop',              'value_ar' => 'تسوق',             'label' => 'Navigation: Shop'],
            ['page' => 'global', 'key' => 'nav.collections',        'value_en' => 'Collections',       'value_ar' => 'المجموعات',        'label' => 'Navigation: Collections'],
            ['page' => 'global', 'key' => 'nav.about',              'value_en' => 'About',             'value_ar' => 'من نحن',           'label' => 'Navigation: About'],
            ['page' => 'global', 'key' => 'whatsapp.cta',           'value_en' => 'Chat with us',      'value_ar' => 'تحدث معنا',        'label' => 'WhatsApp CTA'],
            // Footer
            ['page' => 'global', 'key' => 'footer.brand_description', 'value_en' => 'Made to Be Felt. We craft personalized gifts, timeless pieces, and meaningful moments that last a lifetime.', 'value_ar' => 'صُنع ليُحَس. نصنع هدايا مخصصة وقطعاً خالدة ولحظات ذات معنى تدوم مدى الحياة.', 'type' => 'textarea', 'label' => 'Footer: Brand Description'],
            ['page' => 'global', 'key' => 'footer.copyright',       'value_en' => '© 2026 NIDAN. All rights reserved.', 'value_ar' => '© 2026 نيدان. جميع الحقوق محفوظة.', 'label' => 'Footer: Copyright'],
            ['page' => 'global', 'key' => 'footer.newsletter_placeholder', 'value_en' => 'Enter your email', 'value_ar' => 'أدخل بريدك الإلكتروني', 'label' => 'Footer: Newsletter Placeholder'],
            ['page' => 'global', 'key' => 'footer.newsletter_btn',  'value_en' => 'Join',              'value_ar' => 'انضم',             'label' => 'Footer: Newsletter Button'],
            // Home Hero
            ['page' => 'home', 'key' => 'hero.eyebrow',             'value_en' => 'Welcome to Nidan',  'value_ar' => 'مرحباً بك في نيدان', 'label' => 'Home Hero: Eyebrow'],
            ['page' => 'home', 'key' => 'hero.btn_secondary',       'value_en' => 'Customize Your Gift', 'value_ar' => 'صمّم هديتك',     'label' => 'Home Hero: Secondary Button'],
            // Craft section
            ['page' => 'home', 'key' => 'craft.title',              'value_en' => 'Craft Your Story',  'value_ar' => 'اصنع قصتك',        'label' => 'Craft: Title'],
            ['page' => 'home', 'key' => 'craft.description',        'value_en' => 'Design a unique piece that speaks volumes. Enter a name or meaningful word to see it come to life.', 'value_ar' => 'صمّم قطعة فريدة تعبّر عن الكثير. أدخل اسماً أو كلمة ذات معنى لترى تحولها إلى واقع.', 'type' => 'textarea', 'label' => 'Craft: Description'],
            ['page' => 'home', 'key' => 'craft.btn_create',         'value_en' => 'Create Your Piece', 'value_ar' => 'ابدأ التصميم',     'label' => 'Craft: Create Button'],
            // Cart
            ['page' => 'cart', 'key' => 'cart.drawer.title',        'value_en' => 'Your Cart',         'value_ar' => 'سلة التسوق',       'label' => 'Cart: Drawer Title'],
            ['page' => 'cart', 'key' => 'cart.drawer.empty_message', 'value_en' => 'Your cart is empty', 'value_ar' => 'سلة التسوق فارغة', 'label' => 'Cart: Empty Message'],
            ['page' => 'cart', 'key' => 'cart.drawer.empty_cta',    'value_en' => 'Start Shopping',    'value_ar' => 'ابدأ التسوق',      'label' => 'Cart: Empty CTA'],
            ['page' => 'cart', 'key' => 'cart.drawer.subtotal_label', 'value_en' => 'Subtotal',        'value_ar' => 'المجموع الجزئي',   'label' => 'Cart: Subtotal Label'],
            ['page' => 'cart', 'key' => 'cart.drawer.checkout_btn', 'value_en' => 'Proceed to Checkout', 'value_ar' => 'المتابعة للدفع', 'label' => 'Cart: Checkout Button'],
            // Products
            ['page' => 'product', 'key' => 'product.quick_add',     'value_en' => 'Quick Add',         'value_ar' => 'إضافة سريعة',      'label' => 'Product: Quick Add'],
            ['page' => 'product', 'key' => 'product.add_to_cart',   'value_en' => 'Add to Cart',       'value_ar' => 'أضف للسلة',        'label' => 'Product: Add to Cart'],
            ['page' => 'product', 'key' => 'product.out_of_stock',  'value_en' => 'Out of Stock',      'value_ar' => 'نفذ من المخزون',   'label' => 'Product: Out of Stock'],
            // Checkout
            ['page' => 'checkout', 'key' => 'checkout.place_order', 'value_en' => 'Place Order',       'value_ar' => 'تأكيد الطلب',      'label' => 'Checkout: Place Order'],
            ['page' => 'checkout', 'key' => 'checkout.surprise_label', 'value_en' => 'Hide my identity from recipient (Surprise Mode)', 'value_ar' => 'إخفاء هويتي عن المستلم (وضع المفاجأة)', 'label' => 'Checkout: Surprise Label'],
            ['page' => 'checkout', 'key' => 'checkout.cod',         'value_en' => 'Cash on Delivery / Collection', 'value_ar' => 'الدفع عند الاستلام', 'label' => 'Checkout: COD Label'],
        ];

        foreach ($blocks as $data) {
            $data['type'] = $data['type'] ?? 'text';
            $data['is_rich_text'] = $data['is_rich_text'] ?? false;
            ContentBlock::updateOrCreate(['key' => $data['key']], $data);
        }
    }

    private function seedSeoMeta(): void
    {
        $metas = [
            [
                'page'               => 'home',
                'meta_title_en'      => 'NIDAN — Made to Be Felt | Luxury Gifts in Egypt',
                'meta_title_ar'      => 'نيدان — صُنع ليُحَس | هدايا فاخرة في مصر',
                'meta_description_en' => 'NIDAN — Discover personalized luxury gifts, timeless jewelry, exotic flowers, and Egyptian heritage pieces. Fast delivery across Egypt.',
                'meta_description_ar' => 'نيدان — اكتشف هدايا فاخرة مخصصة، مجوهرات خالدة، زهور رائعة، وقطع تراث مصرية. توصيل سريع في جميع أنحاء مصر.',
                'og_image'           => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1200&q=80',
            ],
            [
                'page'               => 'products',
                'meta_title_en'      => 'Shop All — NIDAN | Luxury Gifts',
                'meta_title_ar'      => 'تسوق الكل — نيدان | هدايا فاخرة',
                'meta_description_en' => 'Browse our full collection of luxury gifts, jewelry, watches, flowers, and Egyptian antiques.',
                'meta_description_ar' => 'استعرض مجموعتنا الكاملة من الهدايا الفاخرة والمجوهرات والساعات والزهور والتحف المصرية.',
            ],
            [
                'page'               => 'checkout',
                'meta_title_en'      => 'Checkout — NIDAN',
                'meta_title_ar'      => 'الدفع — نيدان',
                'meta_description_en' => 'Complete your luxury gift order with NIDAN.',
                'meta_description_ar' => 'أكمل طلب هديتك الفاخرة مع نيدان.',
            ],
        ];

        foreach ($metas as $data) {
            SeoMeta::updateOrCreate(['page' => $data['page']], $data);
        }
    }
}
