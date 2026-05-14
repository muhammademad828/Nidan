<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    <url>
        <loc>{{ route('home', ['locale' => 'en']) }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('collections', ['locale' => 'en']) }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    {{-- Assuming /categories refers to the collections index or similar --}}
    {{-- If there is a separate /categories page, I'd add it here --}}

    {{-- Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('category.show', ['locale' => 'en', 'category' => $category->slug]) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Products --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('product.show', ['locale' => 'en', 'product' => $product->slug]) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>