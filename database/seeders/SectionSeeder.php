<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Hero', 'component_name' => 'hero', 'order' => 1],
            ['name' => 'Services', 'component_name' => 'services', 'order' => 2],
            ['name' => 'Best Sellers', 'component_name' => 'best_sellers', 'order' => 3],
            ['name' => 'Wallets', 'component_name' => 'wallets', 'order' => 4],
            ['name' => 'Jewelry', 'component_name' => 'jewelry', 'order' => 5],
            ['name' => 'Partners', 'component_name' => 'partners', 'order' => 6],
        ];

        foreach ($rows as $data) {
            Section::updateOrCreate(
                ['page' => 'home', 'component_name' => $data['component_name']],
                array_merge($data, ['page' => 'home', 'is_visible' => true])
            );
        }
    }
}
