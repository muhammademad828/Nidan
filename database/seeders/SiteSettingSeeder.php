<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'footer_description',
                'value_en' => 'Curating the heritage of tomorrow. Nidan Atelier brings ancient artistry to modern elegance.',
                'value_ar' => 'نرعى تراث الغد. نيدان أتيليه يجلب الفن القديم إلى الأناقة الحديثة.',
                'group' => 'footer'
            ],
            [
                'key' => 'instagram_url',
                'value_en' => 'https://instagram.com/nidan.atelier',
                'value_ar' => 'https://instagram.com/nidan.atelier',
                'group' => 'social'
            ],
            [
                'key' => 'facebook_url',
                'value_en' => 'https://facebook.com/nidan.atelier',
                'value_ar' => 'https://facebook.com/nidan.atelier',
                'group' => 'social'
            ],
            [
                'key' => 'whatsapp_number',
                'value_en' => '201012345678', // Country code + number
                'value_ar' => '201012345678',
                'group' => 'contact'
            ],
            [
                'key' => 'whatsapp_message',
                'value_en' => 'Hello Nidan Atelier, I would like to inquire about...',
                'value_ar' => 'مرحباً نيدان أتيليه، أود الاستفسار عن...',
                'group' => 'contact'
            ],
            [
                'key' => 'footer_address',
                'value_en' => 'Cairo, Egypt',
                'value_ar' => 'القاهرة، مصر',
                'group' => 'footer'
            ],
            [
                'key' => 'announcement_text',
                'value_en' => 'Eternal Spring — Bespoke Arrangements for Every Occasion — Express 2-Hour Delivery in Cairo —',
                'value_ar' => 'ربيع دائم — ترتيبات مخصصة لكل مناسبة — توصيل سريع خلال ساعتين في القاهرة —',
                'group' => 'general'
            ],
            [
                'key' => 'heritage_outer_bg',
                'value_en' => '#fdfaf5',
                'value_ar' => '#fdfaf5',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_inner_bg',
                'value_en' => '#6b6b6b',
                'value_ar' => '#6b6b6b',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_inner_image',
                'value_en' => 'heritage_bg.png',
                'value_ar' => 'heritage_bg.png',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_top_title',
                'value_en' => 'Timeless Elegance',
                'value_ar' => 'أناقة خالدة',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_main_title',
                'value_en' => 'Heritage Collection',
                'value_ar' => 'مجموعة التراث',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_description',
                'value_en' => 'A tribute to ancient artistry, redesigned to meet modern aesthetics.',
                'value_ar' => 'تحية للفن الفرعوني القديم، أعيد تصميمه ليتناسب مع الجمالية الحديثة.',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_button_text',
                'value_en' => 'Explore Collection',
                'value_ar' => 'استكشف المجموعة',
                'group' => 'heritage'
            ],
            [
                'key' => 'heritage_button_url',
                'value_en' => '/en/collections',
                'value_ar' => '/ar/collections',
                'group' => 'heritage'
            ],
        ];

        foreach ($settings as $s) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
