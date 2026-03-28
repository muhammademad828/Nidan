{{-- Site-wide: header search overlay + mini-cart (native JS in nidan-app.js) --}}
@if(Route::has('products.index'))
<div id="site-search-overlay" class="site-search-overlay" aria-hidden="true">
    <div class="site-search-overlay__backdrop" data-site-search-dismiss tabindex="-1"></div>
    <div class="site-search-overlay__panel" role="dialog" aria-modal="true" aria-labelledby="site-search-heading">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 id="site-search-heading" class="font-headline text-2xl text-on-surface">Search</h2>
            <button type="button" class="scale-95 active:scale-90 transition-transform text-stone-500 hover:text-primary" data-site-search-close aria-label="Close search">
                <span class="material-symbols-outlined" data-icon="close">close</span>
            </button>
        </div>
        <form method="get" action="{{ route('products.index') }}" class="flex flex-col gap-4">
            <label class="sr-only" for="site-search-input">Search products</label>
            <input id="site-search-input" type="search" name="q" value="{{ request('q') }}" autocomplete="off" placeholder="Search the collection…" class="w-full rounded-full border border-outline-variant/50 bg-surface-bright/90 px-6 py-4 font-body text-on-surface placeholder:text-on-surface-variant/70 focus:border-primary/40 focus:ring-2 focus:ring-primary/20 outline-none transition-shadow"/>
            <button type="submit" class="self-start font-label text-[10px] uppercase tracking-[0.25em] text-primary border-b border-primary/40 pb-1 hover:border-primary transition-colors">
                View results
            </button>
        </form>
    </div>
</div>
@endif

@if(Route::has('cart.get'))
<div id="mini-cart" class="mini-cart" aria-hidden="true">
    <div class="mini-cart__backdrop" data-mini-cart-dismiss tabindex="-1"></div>
    <div class="mini-cart__panel" role="dialog" aria-modal="true" aria-labelledby="mini-cart-title">
        <div class="mini-cart__head flex items-center justify-between gap-4 border-b border-outline-variant/30 pb-4 mb-4">
            <h2 id="mini-cart-title" class="font-headline text-xl text-on-surface">Your bag</h2>
            <button type="button" class="scale-95 active:scale-90 text-stone-500 hover:text-primary" data-mini-cart-close aria-label="Close cart">
                <span class="material-symbols-outlined" data-icon="close">close</span>
            </button>
        </div>
        <div class="mini-cart__body no-scrollbar flex-1 overflow-y-auto" data-mini-cart-body>
            <p class="font-body text-sm text-on-surface-variant py-8 text-center">Loading…</p>
        </div>
        <div class="mini-cart__foot border-t border-outline-variant/30 pt-4 mt-4 space-y-4 hidden" data-mini-cart-foot>
            <div class="flex justify-between font-label text-[10px] uppercase tracking-widest text-on-surface-variant">
                <span>Subtotal</span>
                <span data-mini-cart-subtotal>—</span>
            </div>
            @if(Route::has('checkout.index'))
            <a href="{{ route('checkout.index') }}" class="block w-full text-center rounded-full bg-primary text-on-primary font-label text-[10px] uppercase tracking-[0.2em] py-4 hover:opacity-95 transition-opacity" data-mini-cart-checkout>Checkout</a>
            @endif
        </div>
    </div>
</div>
@endif
