<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LocationService
{
    public function getRegions(): array
    {
        return Cache::rememberForever('regions:active', function () {
            return Region::active()->ordered()->get()
                ->map(fn ($r) => [
                    'id'           => $r->id,
                    'name'         => $r->name,
                    'slug'         => $r->slug,
                    'delivery_fee' => (float) $r->delivery_fee,
                ])
                ->toArray();
        });
    }

    public function getCurrentRegion(): ?Region
    {
        $regionId = Session::get('region_id');
        if ($regionId) {
            return Region::find($regionId);
        }
        return Region::active()->ordered()->first();
    }

    public function setRegion(int $regionId): void
    {
        Session::put('region_id', $regionId);
    }

    public function getDeliveryFee(?int $regionId): float
    {
        if (! $regionId) {
            return 0.0;
        }
        return (float) Region::find($regionId)?->delivery_fee ?? 0.0;
    }

    public function buildGeoTitle(string $baseTitle, ?Region $region, string $locale = 'en'): string
    {
        if (! $region) return $baseTitle;

        $regionName = $locale === 'ar' ? $region->name_ar : $region->name_en;

        return $locale === 'ar'
            ? "{$baseTitle} في {$regionName} — نيدان"
            : "{$baseTitle} in {$regionName} — NIDAN";
    }
}
