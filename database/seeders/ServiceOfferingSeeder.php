<?php

namespace Database\Seeders;

use App\Models\ServiceOffering;
use Illuminate\Database\Seeder;

class ServiceOfferingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'icon_material'   => 'auto_awesome',
                'title_en'        => 'Kath Ktab Napkins',
                'title_ar'        => null,
                'description_en'  => 'Custom embroidered artisanal linens for your sacred moments.',
                'description_ar'  => null,
                'button_text_en'  => 'Enquire',
                'button_text_ar'  => null,
                'button_url'      => '/products',
                'display_order'   => 1,
            ],
            [
                'icon_material'   => 'celebration',
                'title_en'        => 'Kosha',
                'title_ar'        => null,
                'description_en'  => 'Bespoke stage designs that capture the essence of your union.',
                'description_ar'  => null,
                'button_text_en'  => 'Discover',
                'button_text_ar'  => null,
                'button_url'      => '/products',
                'display_order'   => 2,
            ],
            [
                'icon_material'   => 'local_florist',
                'title_en'        => 'Engagement Flowers',
                'title_ar'        => null,
                'description_en'  => 'Curated floral arrangements designed for new beginnings.',
                'description_ar'  => null,
                'button_text_en'  => 'View Collection',
                'button_text_ar'  => null,
                'button_url'      => '/products?category=flowers',
                'display_order'   => 3,
            ],
        ];

        foreach ($rows as $data) {
            ServiceOffering::updateOrCreate(
                ['title_en' => $data['title_en']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
