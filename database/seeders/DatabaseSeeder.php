<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            RegionSeeder::class,
            EgyptShippingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CmsSeeder::class,
            HomeLandingSettingsSeeder::class,
            SectionSeeder::class,
            ServiceOfferingSeeder::class,
            PartnerSeeder::class,
        ]);
    }
}
