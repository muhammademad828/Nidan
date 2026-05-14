<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\Category;
use App\Models\Policy;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(HeroSlideSeeder::class);
        
        // Admin user
        User::create([
            'name' => 'Admin Nidan',
            'email' => 'admin@nidanatelier.com',
            'password' => Hash::make('admin123'),
            'phone' => '01012345678',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Customer user
        $customer = User::create([
            'name' => 'Layla Ahmed',
            'email' => 'layla@example.com',
            'password' => Hash::make('password'),
            'phone' => '01011112222',
            'role' => 'user',
            'is_active' => true,
        ]);

        // Vendors
        $vendors = [
            ['name' => 'Cairo Gold Crafts', 'phone' => '01011111111', 'email' => 'info@cairogold.com'],
            ['name' => 'Alexandria Artisans', 'phone' => '01022222222', 'email' => 'info@alexart.com'],
            ['name' => 'Menoufia Blooms', 'phone' => '01033333333', 'email' => 'info@menoufiablooms.com', 'notes' => 'Flower specialist'],
        ];
        foreach ($vendors as $v) {
            Vendor::create(array_merge($v, ['is_active' => true]));
        }

        // Categories
        $categories = [
            ['name_ar' => 'المجوهرات', 'name_en' => 'Jewelry', 'slug' => 'jewelry', 'description_en' => 'Handcrafted luxury jewelry pieces', 'description_ar' => 'قطع مجوهرات فاخرة مصنوعة يدوياً'],
            ['name_ar' => 'الإكسسوارات', 'name_en' => 'Accessories', 'slug' => 'accessories', 'description_en' => 'Premium accessories for every occasion', 'description_ar' => 'إكسسوارات فاخرة لكل مناسبة'],
            ['name_ar' => 'المحافظ', 'name_en' => 'Wallets', 'slug' => 'wallets', 'description_en' => 'Luxury leather wallets', 'description_ar' => 'محافظ جلدية فاخرة'],
            ['name_ar' => 'الورود', 'name_en' => 'Flowers', 'slug' => 'flowers', 'description_en' => 'Bespoke floral arrangements', 'description_ar' => 'ترتيبات ورد فاخرة'],
            ['name_ar' => 'الحرف اليدوية', 'name_en' => 'Handcraft', 'slug' => 'handcraft', 'description_en' => 'Artisanal handcrafted goods', 'description_ar' => 'سلع حرفية يدوية'],
        ];
        foreach ($categories as $c) {
            Category::create(array_merge($c, ['is_active' => true]));
        }

        // Products
        $products = [
            ['name_ar' => 'سلسلة ستانلس ستيل فاخرة', 'name_en' => 'Stainless Steel Chain', 'slug' => 'stainless-steel-chain', 'category_id' => 1, 'vendor_id' => 1, 'cost_price' => 250, 'selling_price' => 450, 'description_en' => 'Premium stainless steel chain, hypoallergenic, waterproof.', 'description_ar' => 'سلسلة ستانلس ستيل فاخرة، مضادة للحساسية، مقاومة للماء.', 'is_customizable' => true, 'image' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'حلية ماسية يدوية الصنع', 'name_en' => 'Handmade Diamond Pendant', 'slug' => 'handmade-diamond-pendant', 'category_id' => 1, 'vendor_id' => 1, 'cost_price' => 600, 'selling_price' => 1200, 'description_en' => 'Handmade pendant with simulated diamond stone.', 'description_ar' => 'حلية يدوية الصنع مع حجر ماسي.', 'is_customizable' => true, 'image' => 'https://images.unsplash.com/photo-1515562141207-7a18b5ce33c7?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'محفظة جلدية فاخرة', 'name_en' => 'Luxury Leather Wallet', 'slug' => 'luxury-leather-wallet', 'category_id' => 3, 'vendor_id' => 2, 'cost_price' => 400, 'selling_price' => 850, 'description_en' => 'Genuine leather wallet with gold stitching.', 'description_ar' => 'محفظة جلد طبيعي مع خياطة ذهبية.', 'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'محفظة بطاقة معدنية', 'name_en' => 'Metal Card Holder', 'slug' => 'metal-card-holder', 'category_id' => 3, 'vendor_id' => 1, 'cost_price' => 150, 'selling_price' => 320, 'description_en' => 'Sleek metal card holder, perfect for corporate gifting.', 'description_ar' => 'حامل بطاقات معدني أنيق.', 'image' => 'https://images.unsplash.com/photo-1574634534894-89d7576c8259?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'باقة ورد الزفاف', 'name_en' => 'Wedding Bouquet', 'slug' => 'wedding-bouquet', 'category_id' => 4, 'vendor_id' => 3, 'cost_price' => 200, 'selling_price' => 500, 'is_flower' => true, 'description_en' => 'Custom wedding bouquet with seasonal flowers.', 'description_ar' => 'باقة ورد الزفاف المخصصة.', 'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'باقة خطوبة', 'name_en' => 'Engagement Rose Bouquet', 'slug' => 'engagement-rose-bouquet', 'category_id' => 4, 'vendor_id' => 3, 'cost_price' => 180, 'selling_price' => 450, 'is_flower' => true, 'description_en' => 'Romantic rose bouquet for your engagement.', 'description_ar' => 'باقة ورد وردي رومانسي لخطوبتك.', 'image' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?q=80&w=400&h=500&auto=format&fit=crop'],
            ['name_ar' => 'ساعة يدوية كلاسيكية', 'name_en' => 'Classic Analog Watch', 'slug' => 'classic-analog-watch', 'category_id' => 2, 'vendor_id' => 2, 'cost_price' => 800, 'selling_price' => 1600, 'description_en' => 'Timeless analog watch with leather strap.', 'description_ar' => 'ساعة أنالوج كلاسيكية بحزام جلدي.', 'image' => 'https://images.unsplash.com/photo-1524592091214-8f97ad300c17?q=80&w=400&h=500&auto=format&fit=crop'],
        ];
        foreach ($products as $p) {
            Product::create(array_merge($p, ['is_active' => true, 'stock' => 99]));
        }

        // Reviews
        $reviewsData = [
            ['product_id' => 1, 'name' => 'Sara M.', 'rating' => 5, 'comment' => 'The quality of this chain is amazing. I wear it every day!', 'status' => 'approved'],
            ['product_id' => 1, 'name' => 'Ahmed K.', 'rating' => 4, 'comment' => 'Very nice product, arrived on time.', 'status' => 'approved'],
            ['product_id' => 3, 'name' => 'Mona S.', 'rating' => 5, 'comment' => 'Luxury at its best. The leather feels so premium.', 'status' => 'approved'],
            ['product_id' => 5, 'name' => 'Nour L.', 'rating' => 5, 'comment' => 'The flowers were fresh and beautifully arranged.', 'status' => 'approved'],
            ['product_id' => 6, 'name' => 'Omar H.', 'rating' => 5, 'comment' => 'Made our engagement day even more special.', 'status' => 'approved'],
            ['product_id' => 7, 'name' => 'Hossam Z.', 'rating' => 4, 'comment' => 'Elegant watch, looks even better in person.', 'status' => 'approved'],
            ['product_id' => 2, 'name' => 'Laila A.', 'rating' => 5, 'comment' => 'Absolutely stunning diamond pendant.', 'status' => 'approved'],
        ];
        foreach ($reviewsData as $r) {
            \App\Models\Review::create(array_merge($r, ['user_id' => $customer->id]));
        }

        // Add-ons
        $addons = [
            ['name_ar' => 'تغليف فاخر', 'name_en' => 'Premium Wrapping', 'price' => 50],
            ['name_ar' => 'شوكولاتة', 'name_en' => 'Chocolate Box', 'price' => 75],
            ['name_ar' => 'بطاقة تهنئة', 'name_en' => 'Gift Card', 'price' => 25],
            ['name_ar' => 'شريط ساتان مخصص', 'name_en' => 'Custom Satin Ribbon', 'price' => 30],
        ];
        foreach ($addons as $a) {
            AddOn::create(array_merge($a, ['is_active' => true, 'sort_order' => 1]));
        }

        // Policies
        $policies = [
            ['type' => 'return', 'title_ar' => 'سياسة الاستبدال والاسترجاع', 'title_en' => 'Return & Refund Policy', 'content_ar' => "يحق للعميل طلب الاستبدال أو الاسترجاع خلال 3 أيام من تاريخ الاستلام.\nيجب أن يكون المنتج في حالته الأصلية غير المستخدم.\nيتم رد المبالغ خلال 7 أيام عمل.", 'content_en' => "Customers may request a return or exchange within 3 days of delivery.\nProducts must be in original, unused condition.\nRefunds are processed within 7 business days.", 'sort_order' => 1],
            ['type' => 'custom_order', 'title_ar' => 'سياسة الطلبات المخصصة', 'title_en' => 'Custom Orders Policy', 'content_ar' => "الطلبات المخصصة غير قابلة للاسترجاع.\nيتم دفع عربون 50% عند تأكيد الطلب.\nالمدة التقريبية للتجهيز: 7-14 يوم عمل.", 'content_en' => "Custom orders are non-refundable.\nA 50% deposit is required upon confirmation.\nProduction time: 7-14 business days.", 'sort_order' => 2],
            ['type' => 'shipping', 'title_ar' => 'سياسة الشحن والتوصيل', 'title_en' => 'Shipping & Delivery Policy', 'content_ar' => "التوصيل متاح في محافظة المنوفية فقط.\nمدة التوصيل: 1-3 أيام عمل.\nتكلفة الشحن تُحسب عند إتمام الطلب.", 'content_en' => "Delivery available within Menoufia governorate only.\nDelivery time: 1-3 business days.\nShipping cost is calculated at checkout.", 'sort_order' => 3],
            ['type' => 'terms', 'title_ar' => 'الشروط والأحكام', 'title_en' => 'Terms & Conditions', 'content_ar' => "بموجب تقديم طلب، فإنك توافق على هذه الشروط والأحكام.\nنحتفظ بالحق في تعديل الأسعار دون إشعار مسبق.\nصور المنتجات لأغراض توضيحية فقط.", 'content_en' => "By placing an order, you agree to these terms and conditions.\nWe reserve the right to modify prices without prior notice.\nProduct images are for illustrative purposes only.", 'sort_order' => 4],
        ];
        foreach ($policies as $p) {
            Policy::create(array_merge($p, ['is_active' => true]));
        }
    }
}
