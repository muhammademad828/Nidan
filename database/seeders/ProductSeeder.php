<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $jewelry    = Category::where('slug', 'jewelry')->first();
        $watches    = Category::where('slug', 'watches')->first();
        $accessories = Category::where('slug', 'accessories')->first();
        $antiques   = Category::where('slug', 'antiques')->first();
        $flowers    = Category::where('slug', 'flowers')->first();

        $products = [
            // Jewelry
            [
                'category_id'         => $jewelry->id,
                'name_en'             => 'Signature Name Necklace',
                'name_ar'             => 'قلادة الاسم المميزة',
                'slug'                => 'signature-name-necklace',
                'short_description_en' => 'Your name, forever wearable. 18k gold plating.',
                'short_description_ar' => 'اسمك، يُرتدى إلى الأبد. طلاء ذهب عيار 18.',
                'base_price'          => 850.00,
                'compare_at_price'    => 1200.00,
                'sku'                 => 'NJW-001',
                'stock_quantity'      => 50,
                'stock_status'        => 'in_stock',
                'is_featured'         => true,
                'display_order'       => 1,
            ],
            [
                'category_id'         => $jewelry->id,
                'name_en'             => 'Heritage Ring — Ankh',
                'name_ar'             => 'خاتم التراث — عنخ',
                'slug'                => 'heritage-ring-ankh',
                'short_description_en' => 'Inspired by the ancient Egyptian symbol of life.',
                'short_description_ar' => 'مستوحى من رمز الحياة المصري القديم.',
                'base_price'          => 1200.00,
                'sku'                 => 'NJW-002',
                'stock_quantity'      => 20,
                'stock_status'        => 'in_stock',
                'is_featured'         => true,
                'display_order'       => 2,
            ],
            [
                'category_id'         => $jewelry->id,
                'name_en'             => 'Engraved Love Bracelet',
                'name_ar'             => 'سوار الحب المنقوش',
                'slug'                => 'engraved-love-bracelet',
                'short_description_en' => 'Sterling silver bracelet with custom engraving.',
                'short_description_ar' => 'سوار فضة إسترليني مع نقش مخصص.',
                'base_price'          => 650.00,
                'sku'                 => 'NJW-003',
                'stock_quantity'      => 35,
                'stock_status'        => 'in_stock',
                'is_featured'         => false,
                'display_order'       => 3,
            ],
            [
                'category_id'         => $jewelry->id,
                'name_en'             => 'Gold Initial Pendant',
                'name_ar'             => 'قلادة حرف ذهبية',
                'slug'                => 'gold-initial-pendant',
                'short_description_en' => 'Delicate 18k gold initial pendant for daily wear.',
                'short_description_ar' => 'قلادة حرف ذهبية رقيقة عيار 18 للارتداء اليومي.',
                'base_price'          => 480.00,
                'sku'                 => 'NJW-004',
                'stock_quantity'      => 60,
                'stock_status'        => 'in_stock',
                'is_featured'         => false,
                'display_order'       => 4,
            ],
            // Watches
            [
                'category_id'         => $watches->id,
                'name_en'             => 'Classic Chronograph',
                'name_ar'             => 'الكرونوغراف الكلاسيكي',
                'slug'                => 'classic-chronograph',
                'short_description_en' => 'Swiss-movement timepiece with sapphire crystal.',
                'short_description_ar' => 'ساعة بحركة سويسرية وزجاج كريستال ياقوتي.',
                'base_price'          => 3200.00,
                'sku'                 => 'NWT-001',
                'stock_quantity'      => 12,
                'stock_status'        => 'low_stock',
                'is_featured'         => true,
                'display_order'       => 1,
            ],
            [
                'category_id'         => $watches->id,
                'name_en'             => 'Rose Gold Elegance',
                'name_ar'             => 'أناقة الذهب الوردي',
                'slug'                => 'rose-gold-elegance',
                'short_description_en' => 'Women\'s dress watch in rose gold with diamond bezel.',
                'short_description_ar' => 'ساعة نسائية من الذهب الوردي مع إطار ماسي.',
                'base_price'          => 4500.00,
                'sku'                 => 'NWT-002',
                'stock_quantity'      => 8,
                'stock_status'        => 'low_stock',
                'is_featured'         => true,
                'display_order'       => 2,
            ],
            // Antiques
            [
                'category_id'         => $antiques->id,
                'name_en'             => 'Golden Bastet Replica',
                'name_ar'             => 'تمثال باستت الذهبي',
                'slug'                => 'golden-bastet-replica',
                'short_description_en' => 'Museum-quality replica of the goddess Bastet in gilded resin.',
                'short_description_ar' => 'نسخة متحفية من الإلهة باستت بالراتينج المذهب.',
                'base_price'          => 950.00,
                'sku'                 => 'NAN-001',
                'stock_quantity'      => 25,
                'stock_status'        => 'in_stock',
                'is_featured'         => true,
                'display_order'       => 1,
            ],
            [
                'category_id'         => $antiques->id,
                'name_en'             => 'Scarab Amulet Collection',
                'name_ar'             => 'مجموعة تمائم الجعران',
                'slug'                => 'scarab-amulet-collection',
                'short_description_en' => 'Set of 3 scarab amulets representing protection and renewal.',
                'short_description_ar' => 'مجموعة من 3 تمائم جعران ترمز للحماية والتجديد.',
                'base_price'          => 680.00,
                'sku'                 => 'NAN-002',
                'stock_quantity'      => 30,
                'stock_status'        => 'in_stock',
                'is_featured'         => false,
                'display_order'       => 2,
            ],
            // Flowers
            [
                'category_id'         => $flowers->id,
                'name_en'             => 'The Eternal Romance',
                'name_ar'             => 'الرومانسية الأبدية',
                'slug'                => 'eternal-romance',
                'short_description_en' => 'Luxury red rose bouquet — 50 stems, same-day delivery.',
                'short_description_ar' => 'باقة ورد أحمر فاخرة — 50 وردة، توصيل في نفس اليوم.',
                'base_price'          => 450.00,
                'sku'                 => 'NFL-001',
                'stock_quantity'      => 100,
                'stock_status'        => 'in_stock',
                'is_featured'         => true,
                'requires_delivery_slot' => true,
                'display_order'       => 1,
            ],
            [
                'category_id'         => $flowers->id,
                'name_en'             => 'Sunrise Garden Bouquet',
                'name_ar'             => 'باقة حديقة الفجر',
                'slug'                => 'sunrise-garden-bouquet',
                'short_description_en' => 'Mixed seasonal blooms in warm sunrise tones.',
                'short_description_ar' => 'زهور موسمية متنوعة بألوان شروق الشمس الدافئة.',
                'base_price'          => 320.00,
                'sku'                 => 'NFL-002',
                'stock_quantity'      => 80,
                'stock_status'        => 'in_stock',
                'is_featured'         => false,
                'requires_delivery_slot' => true,
                'display_order'       => 2,
            ],
            // Accessories
            [
                'category_id'         => $accessories->id,
                'name_en'             => 'Heritage Leather Journal',
                'name_ar'             => 'مفكرة جلد التراث',
                'slug'                => 'heritage-leather-journal',
                'short_description_en' => 'Hand-stitched genuine leather journal with gold foil embossing.',
                'short_description_ar' => 'مفكرة جلد حقيقي مخيطة يدوياً مع نقش ورق ذهبي.',
                'base_price'          => 380.00,
                'sku'                 => 'NAC-001',
                'stock_quantity'      => 40,
                'stock_status'        => 'in_stock',
                'is_featured'         => false,
                'display_order'       => 1,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['sku' => $data['sku']], $data);
        }
    }
}
