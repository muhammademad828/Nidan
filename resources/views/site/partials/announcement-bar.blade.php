@php
    $announce = trim((string) ($siteSettings->announcement_text ?? ''));
    $ve = !empty($visualEditorEnabled);
    $chunks = 6;
    $dur = $announce !== '' ? max(20, min(60, mb_strlen($announce) * 1.2)) : 30;
@endphp
@if($announce !== '')
<div class="site-announcement w-full bg-primary-container/30 py-2" role="region" aria-label="Announcement">
<div class="site-announcement__viewport overflow-hidden">
<div class="site-announcement__track font-label text-[10px] md:text-xs uppercase tracking-[0.2em] text-on-primary-container" style="--announce-marquee-duration: {{ $dur }}s;">
@foreach(range(1, $chunks) as $i)
<span class="site-announcement__chunk inline-flex shrink-0 items-center">
@if($ve && $i === 1)
<span data-editable-key="home|announcement_text" class="inline whitespace-nowrap">{{ $announce }}</span>
@else
<span class="inline whitespace-nowrap" @if($i > 1) aria-hidden="true" @endif>{{ $announce }}</span>
@endif
<span class="site-announcement__sep mx-10 md:mx-14 opacity-35 select-none" aria-hidden="true">✦</span>
</span>
@endforeach
</div>
</div>
</div>
@endif
