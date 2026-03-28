<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for all public pages';

    public function handle(): int
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $now     = now()->toAtomString();

        $urls = collect();

        // Static pages
        foreach (['', '/products'] as $path) {
            foreach (['en', 'ar'] as $locale) {
                $hreflang = $locale === 'en' ? $baseUrl . $path : $baseUrl . $path . '?lang=' . $locale;
                $urls->push([
                    'loc'        => $baseUrl . $path,
                    'lastmod'    => $now,
                    'changefreq' => 'weekly',
                    'priority'   => $path === '' ? '1.0' : '0.8',
                ]);
            }
        }

        // Products
        Product::where('is_active', true)->select('slug', 'updated_at')->each(function ($p) use (&$urls, $baseUrl) {
            $urls->push([
                'loc'        => $baseUrl . '/products/' . $p->slug,
                'lastmod'    => $p->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.7',
            ]);
        });

        // Categories
        Category::where('is_active', true)->select('slug', 'updated_at')->each(function ($c) use (&$urls, $baseUrl) {
            $urls->push([
                'loc'        => $baseUrl . '/products?category=' . $c->slug,
                'lastmod'    => $c->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority'   => '0.6',
            ]);
        });

        // Build XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls->unique('loc') as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        File::put(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated with ' . $urls->unique('loc')->count() . ' URLs → public/sitemap.xml');

        return self::SUCCESS;
    }
}
