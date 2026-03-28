@php
    $ve = !empty($visualEditorEnabled);
    $onHome = request()->routeIs('home');
    $aboutHref = $onHome ? '#about' : (Route::has('home') ? route('home') . '#about' : url('/#about'));
    $contactHref = $onHome ? '#contact' : (Route::has('home') ? route('home') . '#contact' : url('/#contact'));
@endphp
<footer class="w-full rounded-t-[3rem] mt-24 bg-[#eae9de] dark:bg-stone-800 text-[#745b29] dark:text-[#c5a367] flex flex-col md:flex-row justify-between items-center px-16 py-12 gap-8">
<div id="about" class="flex flex-col items-center md:items-start gap-4">
<h4 class="font-['Newsreader'] text-2xl flex flex-col items-center md:items-start gap-2">
@if($siteSettings->logo_url ?? null)
    @include('site.partials.brand-mark', ['imgClass' => 'h-10 w-auto max-w-[min(220px,80vw)] object-contain md:h-11', 'textClass' => ''])
    @if($ve)
    <span data-editable-key="home|footer_brand" class="inline text-center text-sm opacity-80 md:text-start">{{ $siteSettings->footer_brand }}</span>
    @endif
@else
    <span data-editable-key="home|footer_brand" class="inline">{{ $siteSettings->footer_brand }}</span>
@endif
</h4>
<p class="font-['Manrope'] text-[10px] uppercase tracking-[0.2em] opacity-60"><span data-editable-key="home|footer_copyright" class="inline whitespace-pre-line">{{ $siteSettings->footer_copyright }}</span></p>
</div>
<div class="flex flex-wrap justify-center gap-8">
<a class="js-smooth-home font-['Manrope'] text-xs uppercase tracking-[0.2em] text-stone-500 dark:text-stone-400 hover:text-[#745b29] dark:hover:text-white transition-all" href="{{ $aboutHref }}" data-home-anchor="about">About</a>
<a class="js-smooth-home font-['Manrope'] text-xs uppercase tracking-[0.2em] text-stone-500 dark:text-stone-400 hover:text-[#745b29] dark:hover:text-white transition-all" href="{{ $contactHref }}" data-home-anchor="contact">Contact</a>
<a class="font-['Manrope'] text-xs uppercase tracking-[0.2em] text-stone-500 dark:text-stone-400 hover:text-[#745b29] dark:hover:text-white transition-all" href="#"><span @if($ve) data-editable-key="home|footer_link_privacy" @endif class="inline">{{ $siteSettings->footer_link_privacy ?? 'Privacy Policy' }}</span></a>
<a class="font-['Manrope'] text-xs uppercase tracking-[0.2em] text-stone-500 dark:text-stone-400 hover:text-[#745b29] dark:hover:text-white transition-all" href="#"><span @if($ve) data-editable-key="home|footer_link_terms" @endif class="inline">{{ $siteSettings->footer_link_terms ?? 'Terms of Service' }}</span></a>
<a class="font-['Manrope'] text-xs uppercase tracking-[0.2em] text-stone-500 dark:text-stone-400 hover:text-[#745b29] dark:hover:text-white transition-all" href="#"><span @if($ve) data-editable-key="home|footer_link_shipping" @endif class="inline">{{ $siteSettings->footer_link_shipping ?? 'Shipping & Returns' }}</span></a>
</div>
<div id="contact" class="flex items-center gap-6">
@php $ig = $siteSettings->instagram_url ?? null; @endphp
@if($ig)
<a class="opacity-80 hover:opacity-100 transition-opacity js-social-icon" href="{{ $ig }}" rel="noopener noreferrer" target="_blank" aria-label="Instagram">
<span class="material-symbols-outlined text-xl" data-icon="photo_camera">photo_camera</span>
</a>
@else
<a class="opacity-80 hover:opacity-100 transition-opacity" href="#" aria-label="Instagram">
<span class="material-symbols-outlined text-xl" data-icon="photo_camera">photo_camera</span>
</a>
@endif
<a class="opacity-80 hover:opacity-100 transition-opacity" href="#" aria-label="Pinterest">
<span class="material-symbols-outlined text-xl" data-icon="pinterest">pinboard</span>
</a>
@php $fb = $siteSettings->facebook_url ?? null; @endphp
@if($fb)
<a class="opacity-80 hover:opacity-100 transition-opacity js-social-icon" href="{{ $fb }}" rel="noopener noreferrer" target="_blank" aria-label="Facebook">
<span class="material-symbols-outlined text-xl" data-icon="facebook">social_leaderboard</span>
</a>
@else
<a class="opacity-80 hover:opacity-100 transition-opacity" href="#" aria-label="Facebook">
<span class="material-symbols-outlined text-xl" data-icon="facebook">social_leaderboard</span>
</a>
@endif
</div>
</footer>
