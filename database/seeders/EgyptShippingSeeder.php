<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Database\Seeder;

class EgyptShippingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name_ar' => 'القاهرة',         'name_en' => 'Cairo',           'order' => 1,  'cities' => [
                ['ar' => 'مدينة نصر',          'en' => 'Nasr City',           'fee' => 30],
                ['ar' => 'المعادي',             'en' => 'Maadi',               'fee' => 35],
                ['ar' => 'الزمالك',             'en' => 'Zamalek',             'fee' => 35],
                ['ar' => 'مصر الجديدة',         'en' => 'Heliopolis',          'fee' => 30],
                ['ar' => 'المقطم',              'en' => 'El Mokattam',         'fee' => 40],
                ['ar' => 'التجمع الخامس',       'en' => 'New Cairo',           'fee' => 45],
                ['ar' => 'الشروق',              'en' => 'El Shorouk',          'fee' => 55],
                ['ar' => 'العبور',              'en' => 'El Obour',            'fee' => 55],
                ['ar' => 'السلام',              'en' => 'El Salam',            'fee' => 40],
                ['ar' => 'عين شمس',             'en' => 'Ain Shams',           'fee' => 35],
                ['ar' => 'شبرا',               'en' => 'Shubra',              'fee' => 35],
                ['ar' => 'وسط البلد',           'en' => 'Downtown',            'fee' => 35],
                ['ar' => 'حدائق القبة',         'en' => 'Hadayek El Kobba',    'fee' => 35],
                ['ar' => 'المرج',               'en' => 'El Marg',             'fee' => 45],
                ['ar' => 'بدر',                'en' => 'Badr City',           'fee' => 60],
            ]],
            ['name_ar' => 'الجيزة',           'name_en' => 'Giza',            'order' => 2,  'cities' => [
                ['ar' => 'الدقي',              'en' => 'Dokki',               'fee' => 35],
                ['ar' => 'المهندسين',           'en' => 'Mohandessin',         'fee' => 35],
                ['ar' => 'الهرم',              'en' => 'Haram',               'fee' => 40],
                ['ar' => 'فيصل',               'en' => 'Faisal',              'fee' => 40],
                ['ar' => '6 أكتوبر',           'en' => '6th of October',      'fee' => 55],
                ['ar' => 'الشيخ زايد',          'en' => 'Sheikh Zayed',        'fee' => 55],
                ['ar' => 'العجوزة',             'en' => 'Agouza',              'fee' => 35],
                ['ar' => 'الجيزة',              'en' => 'Giza Center',         'fee' => 40],
                ['ar' => 'بولاق الدكرور',       'en' => 'Bulaq Dakrur',        'fee' => 40],
                ['ar' => 'إمبابة',              'en' => 'Imbaba',              'fee' => 40],
            ]],
            ['name_ar' => 'الإسكندرية',       'name_en' => 'Alexandria',      'order' => 3,  'cities' => [
                ['ar' => 'المنتزه',             'en' => 'Montazah',            'fee' => 60],
                ['ar' => 'سيدي جابر',           'en' => 'Sidi Gaber',          'fee' => 60],
                ['ar' => 'الرمل',               'en' => 'Raml Station',        'fee' => 60],
                ['ar' => 'العجمي',              'en' => 'Agami',               'fee' => 65],
                ['ar' => 'برج العرب',           'en' => 'Borg El Arab',        'fee' => 70],
                ['ar' => 'الجمرك',              'en' => 'Gumrok',              'fee' => 60],
                ['ar' => 'بكوس',               'en' => 'Bakous',              'fee' => 65],
                ['ar' => 'المعمورة',            'en' => 'Maamoura',            'fee' => 65],
                ['ar' => 'ميامي',               'en' => 'Miami',               'fee' => 60],
            ]],
            ['name_ar' => 'القليوبية',        'name_en' => 'Qalyubia',        'order' => 4,  'cities' => [
                ['ar' => 'بنها',               'en' => 'Banha',               'fee' => 55],
                ['ar' => 'شبرا الخيمة',         'en' => 'Shubra El Khima',     'fee' => 40],
                ['ar' => 'القناطر الخيرية',     'en' => 'El Qanater',          'fee' => 60],
                ['ar' => 'قليوب',              'en' => 'Qaliub',              'fee' => 55],
                ['ar' => 'طوخ',               'en' => 'Tukh',                'fee' => 60],
            ]],
            ['name_ar' => 'الشرقية',          'name_en' => 'Sharqia',         'order' => 5,  'cities' => [
                ['ar' => 'الزقازيق',            'en' => 'Zagazig',             'fee' => 60],
                ['ar' => 'العاشر من رمضان',     'en' => '10th of Ramadan',     'fee' => 55],
                ['ar' => 'بلبيس',              'en' => 'Belbeis',             'fee' => 65],
                ['ar' => 'أبو كبير',           'en' => 'Abu Kabir',           'fee' => 70],
                ['ar' => 'منيا القمح',          'en' => 'Minya El Qamh',       'fee' => 70],
            ]],
            ['name_ar' => 'الغربية',          'name_en' => 'Gharbia',         'order' => 6,  'cities' => [
                ['ar' => 'طنطا',               'en' => 'Tanta',               'fee' => 65],
                ['ar' => 'المحلة الكبرى',       'en' => 'Mahalla El Kubra',    'fee' => 65],
                ['ar' => 'السنطة',              'en' => 'Samnta',              'fee' => 70],
                ['ar' => 'كفر الزيات',          'en' => 'Kafr El Zayyat',      'fee' => 70],
            ]],
            ['name_ar' => 'المنوفية',         'name_en' => 'Monufia',         'order' => 7,  'cities' => [
                ['ar' => 'شبين الكوم',          'en' => 'Shebin El Kom',       'fee' => 60],
                ['ar' => 'مينا القمح',          'en' => 'Menouf',              'fee' => 65],
                ['ar' => 'أشمون',              'en' => 'Ashmoun',             'fee' => 65],
            ]],
            ['name_ar' => 'البحيرة',          'name_en' => 'Beheira',         'order' => 8,  'cities' => [
                ['ar' => 'دمنهور',              'en' => 'Damanhur',            'fee' => 65],
                ['ar' => 'كفر الدوار',          'en' => 'Kafr El Dawar',       'fee' => 65],
                ['ar' => 'رشيد',               'en' => 'Rosetta',             'fee' => 70],
                ['ar' => 'إيتاي البارود',       'en' => 'Itay El Barud',       'fee' => 70],
            ]],
            ['name_ar' => 'الدقهلية',         'name_en' => 'Dakahlia',        'order' => 9,  'cities' => [
                ['ar' => 'المنصورة',            'en' => 'Mansoura',            'fee' => 60],
                ['ar' => 'طلخا',               'en' => 'Talkha',              'fee' => 65],
                ['ar' => 'ميت غمر',             'en' => 'Mit Ghamr',           'fee' => 65],
                ['ar' => 'دكرنس',              'en' => 'Dekernes',            'fee' => 70],
            ]],
            ['name_ar' => 'الإسماعيلية',      'name_en' => 'Ismailia',        'order' => 10, 'cities' => [
                ['ar' => 'الإسماعيلية',         'en' => 'Ismailia',            'fee' => 65],
                ['ar' => 'القنطرة',             'en' => 'El Qantara',          'fee' => 75],
                ['ar' => 'فايد',               'en' => 'Fayed',               'fee' => 75],
            ]],
            ['name_ar' => 'السويس',           'name_en' => 'Suez',            'order' => 11, 'cities' => [
                ['ar' => 'السويس',              'en' => 'Suez',                'fee' => 65],
                ['ar' => 'الأربعين',            'en' => 'El Arbaeen',          'fee' => 70],
            ]],
            ['name_ar' => 'بورسعيد',          'name_en' => 'Port Said',       'order' => 12, 'cities' => [
                ['ar' => 'بورسعيد',             'en' => 'Port Said',           'fee' => 70],
                ['ar' => 'بورفؤاد',             'en' => 'Port Fouad',          'fee' => 75],
            ]],
            ['name_ar' => 'كفر الشيخ',        'name_en' => 'Kafr El Sheikh',  'order' => 13, 'cities' => [
                ['ar' => 'كفر الشيخ',           'en' => 'Kafr El Sheikh',      'fee' => 70],
                ['ar' => 'دسوق',               'en' => 'Desouq',              'fee' => 75],
                ['ar' => 'فوه',               'en' => 'Fuwwah',              'fee' => 80],
            ]],
            ['name_ar' => 'دمياط',            'name_en' => 'Damietta',        'order' => 14, 'cities' => [
                ['ar' => 'دمياط',               'en' => 'Damietta',            'fee' => 70],
                ['ar' => 'رأس البر',            'en' => 'Ras El Bar',          'fee' => 75],
                ['ar' => 'الزرقا',              'en' => 'Ezbet El Borg',       'fee' => 80],
            ]],
            ['name_ar' => 'المنيا',           'name_en' => 'Minya',           'order' => 15, 'cities' => [
                ['ar' => 'المنيا',              'en' => 'Minya',               'fee' => 75],
                ['ar' => 'ملوي',               'en' => 'Mallawi',             'fee' => 80],
                ['ar' => 'أبوقرقاص',           'en' => 'Abu Qurqas',          'fee' => 80],
            ]],
            ['name_ar' => 'أسيوط',            'name_en' => 'Asyut',           'order' => 16, 'cities' => [
                ['ar' => 'أسيوط',               'en' => 'Asyut',               'fee' => 80],
                ['ar' => 'ديروط',               'en' => 'Dairut',              'fee' => 85],
                ['ar' => 'منفلوط',              'en' => 'Manfalut',            'fee' => 85],
            ]],
            ['name_ar' => 'سوهاج',            'name_en' => 'Sohag',           'order' => 17, 'cities' => [
                ['ar' => 'سوهاج',               'en' => 'Sohag',               'fee' => 80],
                ['ar' => 'أخميم',               'en' => 'Akhmim',              'fee' => 85],
                ['ar' => 'المراغة',             'en' => 'El Maragha',          'fee' => 85],
            ]],
            ['name_ar' => 'قنا',              'name_en' => 'Qena',            'order' => 18, 'cities' => [
                ['ar' => 'قنا',                'en' => 'Qena',                'fee' => 85],
                ['ar' => 'نجع حمادي',           'en' => 'Nag Hammadi',         'fee' => 90],
            ]],
            ['name_ar' => 'الأقصر',           'name_en' => 'Luxor',           'order' => 19, 'cities' => [
                ['ar' => 'الأقصر',              'en' => 'Luxor',               'fee' => 90],
                ['ar' => 'أرمنت',               'en' => 'Armant',              'fee' => 95],
            ]],
            ['name_ar' => 'أسوان',            'name_en' => 'Aswan',           'order' => 20, 'cities' => [
                ['ar' => 'أسوان',               'en' => 'Aswan',               'fee' => 95],
                ['ar' => 'كوم أمبو',            'en' => 'Kom Ombo',            'fee' => 100],
                ['ar' => 'إدفو',               'en' => 'Edfu',                'fee' => 100],
            ]],
            ['name_ar' => 'الفيوم',           'name_en' => 'Fayoum',          'order' => 21, 'cities' => [
                ['ar' => 'الفيوم',              'en' => 'Fayoum',              'fee' => 65],
                ['ar' => 'طامية',               'en' => 'Tamiya',              'fee' => 70],
                ['ar' => 'سنورس',               'en' => 'Sinnuris',            'fee' => 70],
            ]],
            ['name_ar' => 'بني سويف',         'name_en' => 'Beni Suef',       'order' => 22, 'cities' => [
                ['ar' => 'بني سويف',            'en' => 'Beni Suef',           'fee' => 70],
                ['ar' => 'الواسطى',             'en' => 'El Wasta',            'fee' => 75],
            ]],
            ['name_ar' => 'الوادي الجديد',    'name_en' => 'New Valley',      'order' => 23, 'cities' => [
                ['ar' => 'الخارجة',             'en' => 'Kharga',              'fee' => 120],
                ['ar' => 'الداخلة',             'en' => 'Dakhla',              'fee' => 130],
            ]],
            ['name_ar' => 'مطروح',            'name_en' => 'Matruh',          'order' => 24, 'cities' => [
                ['ar' => 'مطروح',               'en' => 'Marsa Matruh',        'fee' => 120],
                ['ar' => 'سيوة',               'en' => 'Siwa',                'fee' => 150],
                ['ar' => 'الحمام',              'en' => 'El Hammam',           'fee' => 90],
                ['ar' => 'العلمين',             'en' => 'El Alamein',          'fee' => 100],
            ]],
            ['name_ar' => 'شمال سيناء',       'name_en' => 'North Sinai',     'order' => 25, 'cities' => [
                ['ar' => 'العريش',              'en' => 'Arish',               'fee' => 100],
                ['ar' => 'الشيخ زويد',          'en' => 'Sheikh Zuweid',       'fee' => 110],
            ]],
            ['name_ar' => 'جنوب سيناء',       'name_en' => 'South Sinai',     'order' => 26, 'cities' => [
                ['ar' => 'شرم الشيخ',           'en' => 'Sharm El Sheikh',     'fee' => 120],
                ['ar' => 'دهب',               'en' => 'Dahab',               'fee' => 130],
                ['ar' => 'طابا',               'en' => 'Taba',                'fee' => 150],
                ['ar' => 'سانت كاترين',         'en' => 'Saint Catherine',     'fee' => 150],
            ]],
            ['name_ar' => 'البحر الأحمر',     'name_en' => 'Red Sea',         'order' => 27, 'cities' => [
                ['ar' => 'الغردقة',             'en' => 'Hurghada',            'fee' => 110],
                ['ar' => 'سفاجا',               'en' => 'Safaga',              'fee' => 120],
                ['ar' => 'القصير',              'en' => 'El Quseir',           'fee' => 130],
                ['ar' => 'مرسى علم',            'en' => 'Marsa Alam',          'fee' => 150],
            ]],
        ];

        foreach ($data as $govData) {
            $gov = Governorate::updateOrCreate(
                ['name_en' => $govData['name_en']],
                [
                    'name_ar'       => $govData['name_ar'],
                    'display_order' => $govData['order'],
                    'is_active'     => true,
                ]
            );

            foreach ($govData['cities'] as $idx => $cityData) {
                City::updateOrCreate(
                    ['governorate_id' => $gov->id, 'name_en' => $cityData['en']],
                    [
                        'name_ar'       => $cityData['ar'],
                        'delivery_fee'  => $cityData['fee'],
                        'is_active'     => true,
                        'display_order' => $idx,
                    ]
                );
            }
        }
    }
}
