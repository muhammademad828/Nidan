@props(['path', 'alt' => '', 'width' => null, 'height' => null, 'class' => ''])

@php
    $isExternal = str_starts_with($path, 'http');
    $src = $isExternal ? $path : asset('storage/' . $path);
    $webpSrc = null;

    if (!$isExternal) {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $webpPath = str_replace('.' . $extension, '.webp', $path);
        
        // We use public_path to check for local file existence
        if (file_exists(public_path('storage/' . $webpPath))) {
            $webpSrc = asset('storage/' . $webpPath);
        }
    }
@endphp

<picture>
    @if($webpSrc)
        <source srcset="{{ $webpSrc }}" type="image/webp">
    @endif
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}" 
        loading="lazy" 
        decoding="async"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
</picture>
