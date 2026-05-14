@php use App\Services\BilingualService; @endphp
<div class="mb-16">
    <form action="{{ url()->current() }}" id="filter-form" method="GET" class="relative group">
        {{-- Preserve other query params like tag, q --}}
        @foreach(request()->except(['min_price', 'max_price', 'sort', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="bg-white/70 backdrop-blur-xl border border-nidan-gold/20 rounded-[2.5rem] p-2 md:p-3 shadow-xl shadow-nidan-text/5 flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
            
            {{-- Filter Label/Icon (Desktop) --}}
            <div class="hidden lg:flex items-center gap-4 px-6 border-e border-nidan-gold/10">
                <div class="w-10 h-10 rounded-full bg-nidan-gold/10 flex items-center justify-center text-nidan-gold">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <span class="text-[11px] uppercase tracking-[0.3em] font-bold text-nidan-text">{{ BilingualService::label('filters') }}</span>
            </div>

            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:flex items-center gap-3 lg:gap-6 p-2 lg:p-0">
                {{-- Price Range Section --}}
                <div class="flex items-center gap-4 bg-white/50 rounded-full px-6 py-2 border border-nidan-gold/5 focus-within:border-nidan-gold/30 transition-all">
                    <span class="text-[10px] uppercase tracking-widest text-nidan-gold font-bold whitespace-nowrap">{{ BilingualService::label('price_range') }}</span>
                    <div class="flex items-center gap-3">
                        <div class="relative group/input min-w-[60px]">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ BilingualService::label('min') }}" 
                                class="w-full bg-transparent border-none focus:ring-0 text-xs font-bold p-0 placeholder-gray-300 text-center">
                            <div class="absolute -bottom-1 left-0 w-0 h-[1px] bg-nidan-gold group-focus-within/input:w-full transition-all duration-500"></div>
                        </div>
                        <span class="text-nidan-gold/20 text-xs">—</span>
                        <div class="relative group/input min-w-[60px]">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ $maxPrice ?? 'Max' }}" 
                                class="w-full bg-transparent border-none focus:ring-0 text-xs font-bold p-0 placeholder-gray-300 text-center">
                            <div class="absolute -bottom-1 left-0 w-0 h-[1px] bg-nidan-gold group-focus-within/input:w-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                {{-- Custom Sort Dropdown --}}
                <div class="relative flex-1 group/dropdown">
                    <div id="custom-sort-btn" class="flex items-center gap-4 bg-white/50 rounded-full px-6 py-2 border border-nidan-gold/5 hover:border-nidan-gold/30 transition-all cursor-pointer">
                        <span class="text-[10px] uppercase tracking-widest text-nidan-gold font-bold whitespace-nowrap">{{ BilingualService::label('sort_by') }}</span>
                        <span id="selected-sort-label" class="text-xs font-bold text-nidan-text truncate">
                            @php
                                $sort = request('sort', 'newest');
                                echo BilingualService::label($sort);
                            @endphp
                        </span>
                        <i class="fas fa-chevron-down ms-auto text-[10px] text-nidan-gold/50 transition-transform group-hover/dropdown:text-nidan-gold"></i>
                    </div>

                    <input type="hidden" name="sort" id="sort-hidden-input" value="{{ request('sort', 'newest') }}">

                    {{-- Dropdown Menu --}}
                    <div id="custom-sort-menu" class="absolute top-full left-0 right-0 mt-3 bg-white/95 backdrop-blur-xl border border-nidan-gold/10 rounded-[1.5rem] shadow-2xl z-[100] opacity-0 invisible translate-y-2 transition-all duration-300 overflow-hidden">
                        @foreach(['newest', 'price_asc', 'price_desc'] as $option)
                            <div class="sort-option px-6 py-3 text-xs font-bold text-nidan-text hover:bg-nidan-gold hover:text-white cursor-pointer transition-colors border-b border-nidan-gold/5 last:border-none" data-value="{{ $option }}">
                                {{ BilingualService::label($option) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3 p-2 lg:p-0">
                @if(request()->hasAny(['min_price', 'max_price', 'sort']))
                    <a href="{{ url()->current() . (request('tag') ? '?tag='.request('tag') : '') . (request('q') ? '?q='.request('q') : '') }}" 
                       class="h-12 px-6 flex items-center justify-center rounded-full text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-undo-alt mr-2"></i>
                        {{ BilingualService::label('clear') }}
                    </a>
                @endif
                <button type="submit" class="h-12 px-10 bg-nidan-text text-white rounded-full text-[10px] uppercase tracking-widest font-bold hover:bg-nidan-gold shadow-lg shadow-nidan-text/10 transition-all flex items-center gap-3 group">
                    <span>{{ BilingualService::label('apply') }}</span>
                    <i class="fas {{ BilingualService::dir() === 'rtl' ? 'fa-arrow-left' : 'fa-arrow-right' }} text-[8px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('custom-sort-btn');
        const menu = document.getElementById('custom-sort-menu');
        const hiddenInput = document.getElementById('sort-hidden-input');
        const selectedLabel = document.getElementById('selected-sort-label');
        const options = document.querySelectorAll('.sort-option');
        const chevron = btn.querySelector('.fa-chevron-down');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('invisible');
            menu.classList.toggle('translate-y-2');
            chevron.classList.toggle('rotate-180');
        });

        options.forEach(opt => {
            opt.addEventListener('click', () => {
                const val = opt.dataset.value;
                const text = opt.innerText;
                hiddenInput.value = val;
                selectedLabel.innerText = text;
                
                menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
                chevron.classList.remove('rotate-180');

                // Optionally auto-submit or let user click Apply
                // document.getElementById('filter-form').submit();
            });
        });

        document.addEventListener('click', () => {
            menu.classList.add('opacity-0', 'invisible', 'translate-y-2');
            chevron.classList.remove('rotate-180');
        });
    });
</script>
