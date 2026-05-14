<?php

namespace App\Services;

class BilingualService
{
    public static function label(string $key, string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        $labels = [
            'home' => ['en' => 'Home', 'ar' => 'الرئيسية'],
            'collections' => ['en' => 'Collections', 'ar' => 'المنتجات'],
            'cart' => ['en' => 'Cart', 'ar' => 'سلة المشتريات'],
            'checkout' => ['en' => 'Checkout', 'ar' => 'إتمام الطلب'],
            'track_order' => ['en' => 'Track Order', 'ar' => 'تتبع الطلب'],
            'contact' => ['en' => 'Contact', 'ar' => 'تواصل معنا'],
            'policies' => ['en' => 'Policies', 'ar' => 'السياسات'],
            'login' => ['en' => 'Login', 'ar' => 'تسجيل الدخول'],
            'account' => ['en' => 'Account', 'ar' => 'حسابي'],
            'logout' => ['en' => 'Logout', 'ar' => 'تسجيل الخروج'],
            'heritage' => ['en' => 'Heritage', 'ar' => 'التراث'],
            'best_sellers' => ['en' => 'Best Sellers', 'ar' => 'الأكثر مبيعاً'],
            'curated_selection' => ['en' => 'Curated Selection', 'ar' => 'مجموعة مختارة'],
            'kind_words' => ['en' => 'Kind Words from Our Circle', 'ar' => 'كلمات طيبة من عملائنا'],
            'explore_collection' => ['en' => 'Explore Collection', 'ar' => 'استكشف المجموعة'],
            'our_expertise' => ['en' => 'Our Expertise', 'ar' => 'خبرتنا'],
            'event_planning' => ['en' => 'Event Planning', 'ar' => 'تنظيم الفعاليات'],
            'timeless_elegance' => ['en' => 'TIMELESS ELEGANCE', 'ar' => 'أناقة خالدة'],
            'heritage_collection' => ['en' => 'The Heritage Collection', 'ar' => 'مجموعة التراث'],
            'heritage_desc' => ['en' => 'A tribute to ancient Pharaonic artistry, reimagined for the modern aesthetic.', 'ar' => 'تحية للفن الفرعوني القديم، أعيد تصميمه ليتناسب مع الجمالية الحديثة.'],
            'contact_artisan' => ['en' => 'Contact Our Artisan', 'ar' => 'تواصل مع الحرفي'],
            'announcement' => ['en' => 'Eternal Spring — Bespoke Arrangements for Every Occasion — Express 2-Hour Delivery in Cairo —', 'ar' => 'ربيع دائم — ترتيبات مخصصة لكل مناسبة — توصيل سريع خلال ساعتين في القاهرة —'],
            'explore_our_world' => ['en' => 'Explore Our World', 'ar' => 'استكشف عالمنا'],
            'products_count' => ['en' => 'products', 'ar' => 'منتجات'],
            'no_categories' => ['en' => 'No categories available.', 'ar' => 'لا توجد فئات متاحة.'],
            'no_products' => ['en' => 'No products in this category.', 'ar' => 'لا توجد منتجات في هذه الفئة.'],
            'cart_empty' => ['en' => 'Your cart is empty.', 'ar' => 'سلة مشترياتك فارغة.'],
            'browse_collection' => ['en' => 'Browse Collection', 'ar' => 'تصفح المجموعة'],
            'remove' => ['en' => 'Remove', 'ar' => 'إزالة'],
            'continue_shopping' => ['en' => 'Continue Shopping', 'ar' => 'مواصلة التسوق'],
            'proceed_to_checkout' => ['en' => 'Proceed to Checkout', 'ar' => 'المتابعة لإتمام الطلب'],
            'customizable' => ['en' => 'Customizable', 'ar' => 'قابل للتخصيص'],
            'flowers' => ['en' => 'Flowers', 'ar' => 'زهور'],
            'starting_from' => ['en' => 'Starting from', 'ar' => 'يبدأ من'],
            'on' => ['en' => 'on', 'ar' => 'على'],
            'our_story' => ['en' => 'Our Story', 'ar' => 'قصتنا'],
            'artisanal_floral' => ['en' => 'Artisanal Floral', 'ar' => 'زهور حرفية'],
            'bespoke_creation' => ['en' => 'Bespoke Creation', 'ar' => 'إبداع مخصص'],
            'quantity' => ['en' => 'Quantity', 'ar' => 'الكمية'],
            'add_to_collection' => ['en' => 'Add to Collection', 'ar' => 'أضف للمجموعة'],
            'customization' => ['en' => 'Customization', 'ar' => 'التخصيص'],
            'customization_desc' => ['en' => 'This product is customizable. Our team will contact you after order.', 'ar' => 'هذا المنتج قابل للتخصيص. سيتواصل معك فريقنا بعد الطلب.'],
            'pharaonic_roots' => ['en' => 'Pharaonic Roots', 'ar' => 'جذور فرعونية'],
            'heritage_craft_desc' => ['en' => 'Every piece in Nidan Atelier is an homage to the heritage of Egypt. Our artisans bridge millennia of history through contemporary form and natural materials.', 'ar' => 'كل قطعة في نيدان أتيليه هي تحية لتراث مصر. يربط حرفيونا آلاف السنين من التاريخ من خلال الشكل المعاصر والمواد الطبيعية.'],
            'heritage_craft_title' => ['en' => 'Heritage Craft', 'ar' => 'حرفة التراث'],
            'heritage_craft_detail' => ['en' => 'Sourced from local artisans who maintain ancient techniques passed through generations.', 'ar' => 'مستمدة من حرفيين محليين يحافظون على تقنيات قديمة متوارثة عبر الأجيال.'],
            'natural_materials_title' => ['en' => 'Natural Materials', 'ar' => 'مواد طبيعية'],
            'natural_materials_detail' => ['en' => 'We use sustainable, locally sourced materials that reflect the desert\'s soul.', 'ar' => 'نستخدم مواد مستدامة ومصادر محلية تعكس روح الصحراء.'],
            'share_experience' => ['en' => 'Share your experience with this creation. Your feedback helps our artisans continue their craft.', 'ar' => 'شارك تجربتك مع هذا العمل. ملاحظاتك تساعد حرفيينا على الاستمرار في حرفتهم.'],
            'rating' => ['en' => 'Rating', 'ar' => 'التقييم'],
            'your_thought' => ['en' => 'Your Thought', 'ar' => 'رأيك'],
            'submit_review' => ['en' => 'Submit Review', 'ar' => 'إرسال المراجعة'],
            'signin_to_review' => ['en' => 'Please sign in to share your review.', 'ar' => 'يرجى تسجيل الدخول لمشاركة مراجعتك.'],
            'signin' => ['en' => 'Sign In', 'ar' => 'تسجيل الدخول'],
            'no_reviews' => ['en' => 'No reviews yet. Be the first to share your thoughts.', 'ar' => 'لا توجد مراجعات بعد. كن أول من يشاركنا رأيه.'],
            'curated_pairings' => ['en' => 'Curated Pairings', 'ar' => 'تنسيقات مختارة'],
            'you_may_also_like' => ['en' => 'You May Also Like', 'ar' => 'قد يعجبك أيضاً'],
            'curating_heritage' => ['en' => 'Curating the heritage of tomorrow.', 'ar' => 'نرعى تراث الغد.'],
            'connect' => ['en' => 'Connect', 'ar' => 'تواصل معنا'],
            'studio' => ['en' => 'Studio', 'ar' => 'الاستوديو'],
            'artisans' => ['en' => 'Artisans', 'ar' => 'الحرفيون'],
            'rights_reserved' => ['en' => 'ALL RIGHTS RESERVED.', 'ar' => 'جميع الحقوق محفوظة.'],
            'privacy' => ['en' => 'Privacy', 'ar' => 'الخصوصية'],
            'terms' => ['en' => 'Terms', 'ar' => 'الشروط'],
            'complimentary_delivery' => ['en' => 'Complimentary Menoufia Delivery', 'ar' => 'توصيل مجاني للمنوفية'],
            'shipping_egypt' => ['en' => 'Shipping Available across Egypt', 'ar' => 'الشحن متوفر لجميع أنحاء مصر'],
            'guest_checkout' => ['en' => 'Guest checkout — no account required', 'ar' => 'إتمام الطلب للزوار — لا يتطلب حساب'],
            'contact_info' => ['en' => 'Contact Information', 'ar' => 'معلومات الاتصال'],
            'full_name' => ['en' => 'Full Name', 'ar' => 'الاسم الكامل'],
            'phone' => ['en' => 'Phone', 'ar' => 'رقم الهاتف'],
            'email' => ['en' => 'Email', 'ar' => 'البريد الإلكتروني'],
            'gender' => ['en' => 'Gender', 'ar' => 'النوع'],
            'prefer_not_to_say' => ['en' => 'Prefer not to say', 'ar' => 'أفضل عدم الذكر'],
            'male' => ['en' => 'Male', 'ar' => 'ذكر'],
            'female' => ['en' => 'Female', 'ar' => 'أنثى'],
            'delivery_details' => ['en' => 'Delivery Details', 'ar' => 'تفاصيل التوصيل'],
            'city' => ['en' => 'City', 'ar' => 'المدينة'],
            'city_note' => ['en' => 'Note: Flower products are only available for Menoufia delivery.', 'ar' => 'ملاحظة: منتجات الزهور متوفرة فقط للتوصيل داخل المنوفية.'],
            'full_address' => ['en' => 'Full Address', 'ar' => 'العنوان الكامل'],
            'order_notes' => ['en' => 'Order Notes (optional)', 'ar' => 'ملاحظات الطلب (اختياري)'],
            'addons_applied' => ['en' => 'Applied to each cart item', 'ar' => 'تُطبق على كل منتج في السلة'],
            'no_addons' => ['en' => 'No add-ons are available right now.', 'ar' => 'لا توجد إضافات متاحة حالياً.'],
            'order_summary' => ['en' => 'Order Summary', 'ar' => 'ملخص الطلب'],
            'items_subtotal' => ['en' => 'Items subtotal', 'ar' => 'إجمالي المنتجات'],
            'shipping' => ['en' => 'Shipping', 'ar' => 'الشحن'],
            'calculated_at_delivery' => ['en' => 'Calculated at delivery', 'ar' => 'يُحسب عند الاستلام'],
            'total' => ['en' => 'Total', 'ar' => 'الإجمالي'],
            'place_order_cod' => ['en' => 'Place Order — Cash on Delivery', 'ar' => 'تأكيد الطلب — الدفع عند الاستلام'],
            'agree_to_terms' => ['en' => 'By placing this order you agree to our', 'ar' => 'بإتمام هذا الطلب أنت توافق على'],
            'track_desc' => ['en' => 'Enter your order number to track your order status', 'ar' => 'أدخل رقم الطلب لتتبع حالته'],
            'order_number' => ['en' => 'Order Number', 'ar' => 'رقم الطلب'],
            'track_button' => ['en' => 'Track My Order', 'ar' => 'تتبع طلبي'],
            'need_help' => ['en' => 'Need help?', 'ar' => 'تحتاج مساعدة؟'],
            'governorate' => ['en' => 'Governorate', 'ar' => 'المحافظة'],
            'select_governorate' => ['en' => 'Select Governorate', 'ar' => 'اختر المحافظة'],
            'select_governorate_first' => ['en' => 'Select Governorate First', 'ar' => 'اختر المحافظة أولاً'],
            'loading' => ['en' => 'Loading...', 'ar' => 'جاري التحميل...'],
            'select_city' => ['en' => 'Select City', 'ar' => 'اختر المدينة'],
            'your_orders' => ['en' => 'Your Orders', 'ar' => 'طلباتك'],
            'order_date' => ['en' => 'Order Date', 'ar' => 'تاريخ الطلب'],
            'view_details' => ['en' => 'View Details', 'ar' => 'عرض التفاصيل'],
            'no_orders_found' => ['en' => 'No orders found in your history.', 'ar' => 'لم يتم العثور على طلبات في سجلك.'],
            'track_logged_desc' => ['en' => 'Here are your recent orders and their current status.', 'ar' => 'إليك طلباتك الأخيرة وحالتها الحالية.'],
            'whatsapp_chat' => ['en' => 'Chat with Us', 'ar' => 'تواصل معنا واتساب'],
            'view_cart' => ['en' => 'View Cart', 'ar' => 'عرض السلة'],
            'subtotal' => ['en' => 'Subtotal', 'ar' => 'الإجمالي الفرعي'],
            'i_agree_to' => ['en' => 'I agree to the', 'ar' => 'أوافق على'],
            'terms_and_conditions' => ['en' => 'Terms and Conditions', 'ar' => 'الشروط والأحكام'],
            'sold_out' => ['en' => 'Sold Out', 'ar' => 'نفذ من المخزون'],
            'out_of_stock' => ['en' => 'Restocking Soon', 'ar' => 'جاري توفيره قريباً'],
            'filters' => ['en' => 'Filters', 'ar' => 'تصفية'],
            'price_range' => ['en' => 'Price Range', 'ar' => 'نطاق السعر'],
            'min' => ['en' => 'Min', 'ar' => 'الأقل'],
            'max' => ['en' => 'Max', 'ar' => 'الأعلى'],
            'apply' => ['en' => 'Apply', 'ar' => 'تطبيق'],
            'clear' => ['en' => 'Clear', 'ar' => 'مسح'],
            'sort_by' => ['en' => 'Sort By', 'ar' => 'ترتيب حسب'],
            'price_asc' => ['en' => 'Price: Low to High', 'ar' => 'السعر: من الأقل للأعلى'],
            'price_desc' => ['en' => 'Price: High to Low', 'ar' => 'السعر: من الأعلى للأقل'],
            'newest' => ['en' => 'Newest Arrivals', 'ar' => 'وصل حديثاً'],
            'egp' => ['en' => 'EGP', 'ar' => 'ج.م'],
            'nidan_atelier' => ['en' => 'Nidan Atelier', 'ar' => 'نيدان أتيليه'],
            'crafted_with_love' => ['en' => 'Crafted with Love in Cairo', 'ar' => 'صُنع بكل حب في القاهرة'],
            'explore_collections' => ['en' => 'Explore Collections', 'ar' => 'استكشف المجموعات'],
        ];

        return $labels[$key][$locale] ?? $key;
    }

    public static function isRtl(string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();
        return in_array($locale, config('bilingual.rtl', []));
    }

    public static function dir(string $locale = null): string
    {
        return self::isRtl($locale) ? 'rtl' : 'ltr';
    }

    public static function localeUrl(string $locale, ?string $currentPath = null): string
    {
        $path = $currentPath ?? request()->path();
        $path = trim((string) $path, '/');
        $path = preg_replace('#^(en|ar)(/|$)#', '', $path) ?? '';
        $path = trim($path, '/');

        return $path === '' ? "/{$locale}" : "/{$locale}/{$path}";
    }
}