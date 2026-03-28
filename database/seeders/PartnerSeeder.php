<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Maison Al Wasl', 'display_order' => 1],
            ['name' => 'Atelier Noor', 'display_order' => 2],
            ['name' => 'Harbor House Events', 'display_order' => 3],
            ['name' => 'Desert Bloom Co.', 'display_order' => 4],
            ['name' => 'Gulf Linens', 'display_order' => 5],
            ['name' => 'Nidan Atelier', 'display_order' => 6],
        ];

        foreach ($rows as $data) {
            Partner::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
