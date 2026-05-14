<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\HeroSlide::create([
            'title_en' => 'Bespoke Blooms & Custom Creations',
            'title_ar' => 'ورود مخصصة وإبداعات فريدة',
            'subtitle_en' => 'Design your dream arrangement for a personalized consultation.',
            'subtitle_ar' => 'صمم ترتيب أحلامك لاستشارة شخصية.',
            'image' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=1200&h=800&auto=format&fit=crop',
            'button_text_en' => 'Explore Collection',
            'button_text_ar' => 'استكشف المجموعة',
            'button_url' => '/en/collections',
            'quote_en' => 'Absolutely stunning craftsmanship',
            'quote_ar' => 'حرفية مذهلة حقاً',
            'quote_author_en' => 'Sarah J.',
            'quote_author_ar' => 'سارة ج.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        \App\Models\HeroSlide::create([
            'title_en' => 'Eternal Spring — Every Occasion',
            'title_ar' => 'ربيع دائم — لكل مناسبة',
            'subtitle_en' => 'Luxury bilingual premium gifting platform.',
            'subtitle_ar' => 'منصة هدايا فاخرة ثنائية اللغة.',
            'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=1200&h=800&auto=format&fit=crop',
            'button_text_en' => 'Our Story',
            'button_text_ar' => 'قصتنا',
            'button_url' => '#',
            'quote_en' => 'The attention to detail is beyond magical.',
            'quote_ar' => 'الاهتمام بالتفاصيل يفوق الخيال.',
            'quote_author_en' => 'Layla M.',
            'quote_author_ar' => 'ليلى م.',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
