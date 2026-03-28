<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name_en' => 'Cairo',        'name_ar' => 'القاهرة',     'slug' => 'cairo',        'delivery_fee' => 50.00,  'display_order' => 1],
            ['name_en' => 'Giza',         'name_ar' => 'الجيزة',      'slug' => 'giza',         'delivery_fee' => 60.00,  'display_order' => 2],
            ['name_en' => 'Alexandria',   'name_ar' => 'الإسكندرية',  'slug' => 'alexandria',   'delivery_fee' => 80.00,  'display_order' => 3],
            ['name_en' => 'Menofia',      'name_ar' => 'المنوفية',    'slug' => 'menofia',      'delivery_fee' => 70.00,  'display_order' => 4],
            ['name_en' => 'Dakahlia',     'name_ar' => 'الدقهلية',    'slug' => 'dakahlia',     'delivery_fee' => 90.00,  'display_order' => 5],
        ];

        foreach ($regions as $data) {
            Region::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
