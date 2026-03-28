<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\CmsService;
use App\View\SiteSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Single entry point for storefront visual editor (text + image), admin-only.
 */
class FrontendApiController extends Controller
{
    /**
     * SiteSetting group => [ key => 'text'|'textarea'|'image' ]
     *
     * @var array<string, array<string, string>>
     */
    private const SITE_SETTING_ALLOWED = [
        'home' => [
            'announcement_text' => 'textarea',
            'hero_title' => 'textarea',
            'hero_title_before' => 'text',
            'hero_title_accent' => 'text',
            'hero_title_after' => 'text',
            'hero_subtitle' => 'textarea',
            'hero_cta_upload' => 'text',
            'hero_image_alt' => 'text',
            'hero_image' => 'image',
            'hero_blob_1' => 'image',
            'hero_blob_2' => 'image',
            'services_eyebrow' => 'text',
            'services_title' => 'text',
            'services_intro' => 'textarea',
            'section_best_sellers' => 'text',
            'section_wallets' => 'text',
            'section_jewelry' => 'text',
            'section_partners' => 'text',
            'empty_best_sellers' => 'textarea',
            'empty_wallets' => 'textarea',
            'empty_jewelry' => 'textarea',
            'footer_brand' => 'text',
            'footer_copyright' => 'text',
            'footer_link_privacy' => 'text',
            'footer_link_terms' => 'text',
            'footer_link_shipping' => 'text',
            'products_shop_eyebrow' => 'text',
            'products_shop_title' => 'text',
            'products_shop_tagline' => 'textarea',
            'products_empty_message' => 'textarea',
            'nav_label_floral' => 'text',
            'nav_label_services' => 'text',
            'nav_label_about' => 'text',
            'nav_label_contact' => 'text',
        ],
        'layout' => [
            'nav_label_floral' => 'text',
            'nav_label_services' => 'text',
            'nav_label_about' => 'text',
            'nav_label_contact' => 'text',
        ],
        'branding' => [
            'logo' => 'image',
            'favicon' => 'image',
        ],
    ];

    /** @var array<string, class-string> */
    private const MODEL_BY_TABLE = [
        'Product' => Product::class,
        'ProductDescription' => Product::class,
    ];

    private const PRODUCT_TEXT_COLUMNS = [
        'name_en', 'name_ar',
        'short_description_en', 'short_description_ar',
        'description_en', 'description_ar',
    ];

    private const PRODUCT_IMAGE_COLUMNS = [
        'primary_image_path',
    ];

    public function update(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            return $this->updateImage($request);
        }

        return $this->updateText($request);
    }

    private function updateText(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => ['nullable', 'string', 'max:160'],
            'table' => ['nullable', 'string', 'max:64'],
            'id' => ['nullable', 'integer', 'min:1'],
            'column' => ['nullable', 'string', 'max:64'],
            'value' => ['nullable', 'string', 'max:20000'],
        ]);

        if (! empty($validated['table']) && $validated['id'] !== null && ! empty($validated['column'])) {
            $this->updateModelText(
                (string) $validated['table'],
                (int) $validated['id'],
                (string) $validated['column'],
                $validated['value'] ?? ''
            );
        } elseif (! empty($validated['key'])) {
            $this->updateSiteSettingText((string) $validated['key'], $validated['value'] ?? '');
        } else {
            throw ValidationException::withMessages([
                'key' => 'Provide key (site setting) or table, id, and column.',
            ]);
        }

        $this->bustCaches();

        return response()->json(['ok' => true]);
    }

    private function updateImage(Request $request): JsonResponse
    {
        $request->validate([
            'key' => ['nullable', 'string', 'max:160'],
            'table' => ['nullable', 'string', 'max:64'],
            'id' => ['nullable', 'integer', 'min:1'],
            'column' => ['nullable', 'string', 'max:64'],
            'image' => ['required', 'image', 'max:8192', 'mimes:jpeg,jpg,png,webp,gif,avif,svg'],
        ]);

        if ($request->filled('table') && $request->input('id') !== null && $request->filled('column')) {
            $url = $this->updateModelImage(
                (string) $request->input('table'),
                (int) $request->input('id'),
                (string) $request->input('column'),
                $request->file('image')
            );
        } elseif ($request->filled('key')) {
            $url = $this->updateSiteSettingImage((string) $request->input('key'), $request->file('image'));
        } else {
            throw ValidationException::withMessages([
                'key' => 'Provide key or table, id, and column for image upload.',
            ]);
        }

        $this->bustCaches();

        return response()->json(['ok' => true, 'url' => $url]);
    }

    private function updateSiteSettingText(string $rawKey, string $value): void
    {
        [$group, $key] = $this->parseSettingKey($rawKey);
        $this->assertSiteSettingAllowed($group, $key, false);

        $type = self::SITE_SETTING_ALLOWED[$group][$key];
        if ($type === 'image') {
            throw ValidationException::withMessages(['key' => 'Use multipart file upload for this key.']);
        }

        $this->persistSiteSetting($group, $key, $value, $type);
    }

    private function updateSiteSettingImage(string $rawKey, $uploadedFile): string
    {
        [$group, $key] = $this->parseSettingKey($rawKey);
        $this->assertSiteSettingAllowed($group, $key, true);

        $path = $uploadedFile->store('visual-editor', 'public');
        $this->persistSiteSetting($group, $key, $path, 'image');

        return asset('storage/'.ltrim($path, '/'));
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseSettingKey(string $raw): array
    {
        $parts = explode('|', $raw, 2);
        if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
            throw ValidationException::withMessages(['key' => 'Invalid key format. Use group|key.']);
        }

        return [$parts[0], $parts[1]];
    }

    private function assertSiteSettingAllowed(string $group, string $key, bool $imageUpload): void
    {
        if (! isset(self::SITE_SETTING_ALLOWED[$group][$key])) {
            throw ValidationException::withMessages(['key' => 'This field cannot be edited from the visual editor.']);
        }
        $type = self::SITE_SETTING_ALLOWED[$group][$key];
        if ($imageUpload && $type !== 'image') {
            throw ValidationException::withMessages(['key' => 'This key expects text, not an image.']);
        }
        if (! $imageUpload && $type === 'image') {
            throw ValidationException::withMessages(['value' => 'This key expects an image upload.']);
        }
    }

    private function persistSiteSetting(string $group, string $key, string $value, string $type): void
    {
        $existing = SiteSetting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        SiteSetting::updateOrCreate(
            ['group' => $group, 'key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'label' => $existing?->label ?: ('Visual editor — '.$group.'.'.$key),
            ]
        );
    }

    private function resolveModelClass(string $table): string
    {
        $normalized = $this->normalizeTableName($table);
        if (! isset(self::MODEL_BY_TABLE[$normalized])) {
            throw ValidationException::withMessages(['table' => 'Unsupported table for visual editing.']);
        }

        return self::MODEL_BY_TABLE[$normalized];
    }

    private function normalizeTableName(string $table): string
    {
        $t = str_replace(['\\', '/'], '', $table);
        $base = class_basename($t);
        $lower = strtolower($base);
        $aliases = [
            'product' => 'Product',
            'products' => 'Product',
            'productdescription' => 'ProductDescription',
        ];

        return $aliases[$lower] ?? $base;
    }

    private function updateModelText(string $table, int $id, string $column, string $value): void
    {
        $class = $this->resolveModelClass($table);

        if ($class !== Product::class) {
            throw ValidationException::withMessages(['table' => 'Unsupported model.']);
        }

        if (! in_array($column, self::PRODUCT_TEXT_COLUMNS, true)) {
            throw ValidationException::withMessages(['column' => 'This column cannot be edited from the visual editor.']);
        }

        $product = Product::query()->whereKey($id)->first();
        if (! $product) {
            throw ValidationException::withMessages(['id' => 'Product not found.']);
        }

        $product->forceFill([$column => $value])->save();
    }

    private function updateModelImage(string $table, int $id, string $column, $uploadedFile): string
    {
        $class = $this->resolveModelClass($table);

        if ($class !== Product::class) {
            throw ValidationException::withMessages(['table' => 'Unsupported model.']);
        }

        if (! in_array($column, self::PRODUCT_IMAGE_COLUMNS, true)) {
            throw ValidationException::withMessages(['column' => 'This column does not accept image uploads.']);
        }

        $product = Product::query()->whereKey($id)->first();
        if (! $product) {
            throw ValidationException::withMessages(['id' => 'Product not found.']);
        }

        $path = $uploadedFile->store('products/visual-editor', 'public');
        $product->forceFill([$column => $path])->save();

        return Product::publicImageUrl($path) ?? asset('storage/'.ltrim($path, '/'));
    }

    private function bustCaches(): void
    {
        CmsService::invalidateForeverCaches();
        SiteSettings::flushRequestMemo();
    }
}
