<?php

namespace App\View;

use App\Services\CartService;

/**
 * Per-request extras for storefront controllers ($siteSettings comes from View composer + cache).
 */
class SiteStorefrontData
{
    /**
     * @return array<string, mixed>
     */
    public static function shared(): array
    {
        return [
            'cartItemCount' => app(CartService::class)->getCart()->item_count,
        ];
    }
}
