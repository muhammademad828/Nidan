<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name_en'        => 'Personalized Jewelry',
                'name_ar'        => 'المجوهرات المخصصة',
                'slug'           => 'jewelry',
                'description_en' => 'Name necklaces, engraved rings, bespoke pieces in 18k gold and sterling silver.',
                'description_ar' => 'قلائد الأسماء، الخواتم المنقوشة، وقطع فريدة من الذهب عيار 18 والفضة الإسترليني.',
                'display_order'  => 1,
                'meta_title_en'  => 'Personalized Jewelry — NIDAN',
                'meta_title_ar'  => 'مجوهرات مخصصة — نيدان',
            ],
            [
                'name_en'        => 'Luxury Watches',
                'name_ar'        => 'الساعات الفاخرة',
                'slug'           => 'watches',
                'description_en' => 'Timepieces that transcend — from classic dress watches to bold chronographs.',
                'description_ar' => 'ساعات تتجاوز الزمن — من الساعات الكلاسيكية إلى الكرونوغراف الجريء.',
                'display_order'  => 2,
                'meta_title_en'  => 'Luxury Watches — NIDAN',
                'meta_title_ar'  => 'ساعات فاخرة — نيدان',
            ],
            [
                'name_en'        => 'Fine Accessories',
                'name_ar'        => 'الإكسسوارات الراقية',
                'slug'           => 'accessories',
                'description_en' => 'Curated leather goods, silk scarves, and refined everyday luxuries.',
                'description_ar' => 'منتجات جلدية منتقاة، أوشحة حريرية، وكماليات يومية راقية.',
                'display_order'  => 3,
                'meta_title_en'  => 'Fine Accessories — NIDAN',
                'meta_title_ar'  => 'إكسسوارات راقية — نيدان',
            ],
            [
                'name_en'        => 'Egyptian Antiques',
                'name_ar'        => 'التحف المصرية',
                'slug'           => 'antiques',
                'description_en' => 'Museum-grade replicas connecting you to 3,000 years of pharaonic history.',
                'description_ar' => 'نسخ متحفية تربطك بـ 3000 عام من التاريخ الفرعوني.',
                'display_order'  => 4,
                'meta_title_en'  => 'Egyptian Antiques — NIDAN',
                'meta_title_ar'  => 'تحف مصرية — نيدان',
            ],
            [
                'name_en'        => 'Flower Bouquets',
                'name_ar'        => 'باقات الزهور',
                'slug'           => 'flowers',
                'description_en' => 'Handcrafted arrangements from fresh blooms to eternal dried florals.',
                'description_ar' => 'تنسيقات مصنوعة يدوياً من الزهور الطازجة إلى الزهور المجففة الأبدية.',
                'display_order'  => 5,
                'meta_title_en'  => 'Flower Bouquets — NIDAN',
                'meta_title_ar'  => 'باقات زهور — نيدان',
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
