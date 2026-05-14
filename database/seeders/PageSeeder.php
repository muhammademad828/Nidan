<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'slug' => 'our-story',
            'title_ar' => 'قصتنا',
            'title_en' => 'Our Story',
            'content_ar' => '<p>بدأت قصة نيدان بشغف للجمال والورد الطبيعي...</p>',
            'content_en' => '<p>Nidan\'s story began with a passion for beauty and natural roses...</p>',
            'is_active' => true,
        ]);
    }
}
