<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['en' => 'Graduation Gifts', 'ar' => 'هدايا التخرج'],
            ['en' => 'Best Sellers', 'ar' => 'الأكثر مبيعاً'],
            ['en' => 'Seasonal Specials', 'ar' => 'عروض موسمية'],
        ];

        foreach ($tags as $t) {
            $tag = Tag::create([
                'name_en' => $t['en'],
                'name_ar' => $t['ar'],
                'slug' => Str::slug($t['en']),
            ]);

            // Assign to some products
            $products = Product::inRandomOrder()->limit(5)->get();
            foreach ($products as $product) {
                $product->tags()->attach($tag->id);
            }

            // Create home section for the first two
            if ($t['en'] !== 'Seasonal Specials') {
                HomeSection::create([
                    'title_en' => $t['en'] === 'Graduation Gifts' ? 'Celebrate Graduation with Nidan' : 'Our Most Loved Pieces',
                    'title_ar' => $t['en'] === 'Graduation Gifts' ? 'احتفل بالتخرج مع نيدان' : 'قطعنا الأكثر حبًا',
                    'tag_id' => $tag->id,
                    'sort_order' => $t['en'] === 'Graduation Gifts' ? 1 : 2,
                    'is_active' => true,
                ]);
            }
        }
    }
}
