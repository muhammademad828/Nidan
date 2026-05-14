@php
    use App\Services\BilingualService;
    use App\Models\SiteSetting;

    $footerDesc = SiteSetting::getByKey('footer_description', 'Timeless artistry, woven into every bloom. Cairo.');
    $instagram = SiteSetting::getByKey('instagram_url', '#');
    $facebook = SiteSetting::getByKey('facebook_url', '#');
    $waNumber = SiteSetting::getByKey('whatsapp_number', '201012345678');
    $waMessage = SiteSetting::getByKey('whatsapp_message', 'Hello Nidan Atelier...');
    $footerAddress = SiteSetting::getByKey('footer_address', 'Cairo, Egypt');
@endphp

<footer class="w-full bg-[#fdfaf5] pt-12 pb-6 flex flex-col items-center border-t border-nidan-text/[0.05] relative z-10">
    <div class="w-full max-w-7xl mx-auto px-6 md:px-12 flex flex-col">

        <!-- Compact Top Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <!-- Brand Column -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left">
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Nidan" class="w-[120px] h-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                </a>
                <p class="font-serif italic text-nidan-text/60 text-sm leading-relaxed max-w-xs">
                    {{ $footerDesc }}
                </p>
            </div>

            <!-- Links Column -->
            <div class="flex flex-col items-center">
                <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                    <a href="{{ route('collections') }}" class="text-[11px] uppercase tracking-[0.2em] text-nidan-text/50 hover:text-nidan-gold transition-colors font-bold">{{ BilingualService::label('collections') }}</a>
                    <a href="{{ route('page.show', ['locale' => app()->getLocale(), 'slug' => 'our-story']) }}" class="text-[11px] uppercase tracking-[0.2em] text-nidan-text/50 hover:text-nidan-gold transition-colors font-bold">{{ app()->getLocale() == 'ar' ? 'قصتنا' : 'Our Story' }}</a>
                    <a href="{{ route('home') }}#heritage-section" class="text-[11px] uppercase tracking-[0.2em] text-nidan-text/50 hover:text-nidan-gold transition-colors font-bold">{{ BilingualService::label('heritage') }}</a>
                    <a href="{{ route('track') }}" class="text-[11px] uppercase tracking-[0.2em] text-nidan-text/50 hover:text-nidan-gold transition-colors font-bold">{{ BilingualService::label('track_order') }}</a>
                    <a href="{{ route('policy', ['locale' => app()->getLocale(), 'type' => 'terms']) }}" class="text-[11px] uppercase tracking-[0.2em] text-nidan-text/50 hover:text-nidan-gold transition-colors font-bold">{{ BilingualService::label('terms') }}</a>
                </div>
                <div class="flex gap-6 mt-6">
                    <a href="{{ $instagram }}" target="_blank" class="text-nidan-text/30 hover:text-nidan-gold transition-all"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="{{ $facebook }}" target="_blank" class="text-nidan-text/30 hover:text-nidan-gold transition-all"><i class="fab fa-facebook-f text-lg"></i></a>
                </div>
            </div>

            <!-- Contact Column -->
            <div class="flex flex-col items-center md:items-end text-center md:text-right">
                <h4 class="text-[10px] uppercase tracking-[0.3em] text-nidan-gold font-bold mb-3">{{ BilingualService::label('connect') }}</h4>
                <address class="not-italic text-xs text-nidan-text/50 space-y-2">
                    <p>{{ $footerAddress }}</p>
                    <p><a href="mailto:hello@nidanatelier.com" class="hover:text-nidan-gold transition-colors">hello@nidanatelier.com</a></p>
                </address>
            </div>
        </div>

        <!-- Minimal Bottom Section -->
        <div class="pt-6 border-t border-nidan-text/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[9px] uppercase tracking-[0.2em] text-nidan-text/30 font-bold">
                &copy; {{ date('Y') }} Nidan Atelier. {{ BilingualService::label('rights_reserved') }}
            </p>
            <div class="flex items-center gap-6">
                <span class="text-[9px] uppercase tracking-[0.3em] text-nidan-gold/50 font-bold italic">Bespoke Elegance</span>
                <p class="text-[9px] uppercase tracking-[0.2em] text-nidan-text/30 font-bold">Cairo &bull; Dubai &bull; Paris</p>
            </div>
        </div>
    </div>
</footer>

<!-- Minimal Gold Expanding WhatsApp Button -->
<div class="fixed bottom-8 right-8 z-[100]" dir="ltr">
    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}" target="_blank"
       class="flex items-center bg-nidan-gold text-white rounded-full shadow-[0_15px_40px_rgba(212,184,126,0.4)] transition-all duration-700 ease-in-out transform hover:-translate-y-2 group max-w-[56px] hover:max-w-[220px] h-14 overflow-hidden relative">
        
        {{-- Label (Revealed on hover) --}}
        <div class="overflow-hidden">
            <span class="whitespace-nowrap text-[11px] uppercase tracking-[0.2em] font-bold opacity-0 group-hover:opacity-100 transition-all duration-700 transform translate-x-8 group-hover:translate-x-0 inline-block ps-8 pe-2">
                {{ BilingualService::label('contact') }}
            </span>
        </div>

        {{-- Icon (Always visible) --}}
        <div class="w-14 h-14 flex items-center justify-center shrink-0">
            <i class="fab fa-whatsapp text-2xl transition-transform duration-500 group-hover:scale-110"></i>
        </div>

        {{-- Luxury Shine Effect --}}
        <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-40 group-hover:animate-shine"></div>
    </a>
</div>

<style>
@keyframes shine {
    100% {
        left: 125%;
    }
}
.group-hover\:animate-shine:hover {
    animation: shine 0.8s ease-in-out;
}
</style>

