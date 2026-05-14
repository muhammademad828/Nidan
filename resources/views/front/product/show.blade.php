@extends('layouts.app')
@php use App\Services\SeoService; use App\Services\BilingualService; @endphp

@section('seo_title', SeoService::getProductTitle($product))
@section('seo_desc', SeoService::getProductDescription($product))
@section('seo_image', $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('logo.png'))

@section('content')
<main class="max-w-7xl mx-auto px-6 md:px-12 py-12 lg:py-24 relative z-10">
    <!-- Breadcrumbs -->
    <nav class="mb-12">
        <ol class="flex text-[10px] tracking-[0.2em] uppercase text-gray-500 gap-4">
            <li><a class="hover:text-nidan-gold transition-colors" href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ BilingualService::label('home') }}</a></li>
            <li class="text-gray-300">/</li>
            <li><a class="hover:text-nidan-gold transition-colors" href="{{ route('collections', ['locale' => app()->getLocale()]) }}">{{ BilingualService::label('collections') }}</a></li>
            @if($product->category)
            <li class="text-gray-300">/</li>
            <li><a class="hover:text-nidan-gold transition-colors" href="{{ route('category.show', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="text-gray-300">/</li>
            <li class="text-nidan-text font-semibold">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Hero Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 mb-24">
        <!-- Left Column: Image Gallery -->
        <div class="space-y-6 max-w-md mx-auto lg:max-w-full w-full">
            <div id="zoom-container" class="relative group aspect-square lg:aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-gray-100 shadow-sm border border-gray-100 cursor-zoom-in">
                <x-optimized-image 
                    id="main-product-image"
                    :path="$product->image ?? 'placeholder.jpg'"
                    :alt="$product->name"
                    class="w-full h-full object-cover transition-transform duration-300 pointer-events-none"
                />
            </div>
            
            <div class="grid grid-cols-5 gap-4">
                @if($product->image)
                <div class="aspect-square rounded-2xl overflow-hidden border-2 border-nidan-gold cursor-pointer thumbnail-btn active-thumb transition-all" data-src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}">
                    <img class="w-full h-full object-cover" src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"/>
                </div>
                @endif
                
                @if($product->images && count($product->images) > 0)
                    @foreach($product->images as $img)
                    <div class="aspect-square rounded-2xl overflow-hidden border-2 border-transparent hover:border-nidan-gold/50 cursor-pointer transition-all thumbnail-btn" data-src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}">
                        <img class="w-full h-full object-cover opacity-70 hover:opacity-100 transition-opacity" src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}"/>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Column: Product Details -->
        <div class="flex flex-col justify-center">
            @if($product->is_flower)
                <span class="text-[10px] tracking-[0.4em] uppercase text-nidan-gold font-bold mb-4 block">{{ BilingualService::label('artisanal_floral') }}</span>
            @else
                <span class="text-[10px] tracking-[0.4em] uppercase text-nidan-gold font-bold mb-4 block">{{ BilingualService::label('bespoke_creation') }}</span>
            @endif
            
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl text-nidan-text mb-6 leading-tight">{{ $product->name }}</h1>
            <div class="flex items-baseline gap-4 mb-8">
                <p class="text-2xl text-nidan-text/80 font-light">EGP {{ number_format($product->selling_price, 2) }}</p>
                @if($product->sku)
                    <span class="text-[10px] tracking-widest text-gray-400 font-mono uppercase bg-gray-50 px-2 py-1 rounded">Code: {{ $product->sku }}</span>
                @endif
            </div>
            
            <div class="mb-10">
                <p class="text-gray-500 leading-relaxed max-w-md font-light text-sm md:text-base">
                    {{ $product->description }}
                </p>
            </div>

            <!-- Add to Cart Form -->
            <form id="add-to-cart-form" method="POST" data-cart-add-url="{{ route('cart.add', ['locale' => app()->getLocale()]) }}" data-cart-redirect-url="{{ route('cart', ['locale' => app()->getLocale()]) }}" class="mb-12">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="space-y-8 mb-10">
                    @if($product->is_customizable)
                    <div>
                        <span class="text-[10px] tracking-[0.2em] uppercase text-gray-400 block mb-3 font-semibold">{{ BilingualService::label('customization') }}</span>
                        <div class="p-5 bg-white/50 border border-nidan-gold/20 rounded-2xl text-sm text-gray-600 backdrop-blur-sm">
                            {{ BilingualService::label('customization_desc') }}
                        </div>
                    </div>
                    @endif

                    <div>
                        <span class="text-[10px] tracking-[0.2em] uppercase text-gray-400 block mb-3 font-semibold">{{ BilingualService::label('quantity') }}</span>
                        <div class="flex items-center border border-gray-200 bg-white w-32 justify-between px-4 py-3 rounded-xl shadow-sm">
                            <i class="fas fa-minus text-sm cursor-pointer select-none text-gray-400 hover:text-nidan-gold quantity-btn transition-colors" data-action="decrease"></i>
                            <input type="number" name="quantity" value="1" min="1" max="99" class="w-10 text-center border-none focus:ring-0 bg-transparent text-sm font-bold text-nidan-text p-0 m-0" id="quantity-input">
                            <i class="fas fa-plus text-sm cursor-pointer select-none text-gray-400 hover:text-nidan-gold quantity-btn transition-colors" data-action="increase"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" @disabled($product->stock <= 0) 
                    class="w-full py-5 {{ $product->stock <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-nidan-text hover:bg-nidan-gold shadow-nidan-text/20 hover:shadow-nidan-gold/30' }} text-white rounded-full shadow-lg transition-all duration-500 tracking-[0.2em] uppercase text-xs font-bold flex items-center justify-center gap-3 group">
                    <span>{{ $product->stock <= 0 ? BilingualService::label('out_of_stock') : BilingualService::label('add_to_collection') }}</span>
                    <i class="fas fa-shopping-bag opacity-70 group-hover:opacity-100 transition-opacity"></i>
                </button>
            </form>

            <div class="flex items-center gap-3 text-gray-400 text-[10px] tracking-[0.2em] uppercase pt-8 border-t border-gray-200/60">
                <i class="fas fa-truck text-nidan-gold text-lg"></i>
                <span class="font-medium">{{ $product->is_flower ? BilingualService::label('complimentary_delivery') : BilingualService::label('shipping_egypt') }}</span>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($related && $related->count() > 0)
    <section class="py-24 border-t border-gray-200/60 bg-[#faf8f5]/30">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-[10px] tracking-[0.4em] uppercase text-nidan-gold font-bold block mb-4">{{ BilingualService::label('curated_pairings') }}</span>
                <h3 class="font-serif text-4xl text-nidan-text">{{ BilingualService::label('you_may_also_like') }}</h3>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
                @foreach($related as $rel)
                <article class="group">
                    <a href="{{ route('product.show', ['product' => $rel->slug]) }}" class="block">
                        <div class="relative aspect-[4/5] rounded-[2.5rem] overflow-hidden bg-gray-100 mb-8 shadow-sm group-hover:shadow-2xl transition-all duration-700">
                            <img src="{{ $rel->image ? (str_starts_with($rel->image, 'http') ? $rel->image : asset('storage/' . $rel->image)) : 'https://via.placeholder.com/400x500?text=Nidan+Atelier' }}" 
                                 alt="{{ $rel->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            
                            <!-- Quick View Overlay -->
                            <div class="absolute inset-0 bg-nidan-text/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                <span class="bg-white text-nidan-text text-[10px] font-bold uppercase tracking-[0.2em] px-6 py-3 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    {{ BilingualService::label('view_details') ?? 'View Details' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col text-center px-2">
                            <span class="text-[9px] uppercase tracking-[0.3em] text-nidan-gold font-bold mb-3 block opacity-80">
                                {{ $rel->category?->name ?? 'Collection' }}
                            </span>
                            <h4 class="text-lg font-serif text-nidan-text mb-2 group-hover:text-nidan-gold transition-colors duration-300">
                                {{ $rel->name }}
                            </h4>
                            <p class="text-sm font-light text-gray-500">
                                EGP {{ number_format($rel->selling_price, 2) }}
                            </p>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Reviews Section -->
    <section class="py-24 border-t border-gray-200/60 relative">
        <!-- Subtle background pattern or distinct color to separate the section -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#faf8f5] -z-10"></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16 lg:gap-24 relative z-10">
            <div class="lg:col-span-1">
                <span class="text-[10px] tracking-[0.3em] uppercase text-nidan-gold font-bold block mb-4">Testimonials</span>
                <h3 class="font-serif text-4xl text-nidan-text mb-4">{{ BilingualService::label('kind_words') }}</h3>
                <p class="text-gray-600 text-sm mb-10 leading-relaxed font-light">
                    {{ BilingualService::label('share_experience') }}
                </p>

                @auth
                    <form action="{{ route('reviews.store', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-6 bg-white p-8 rounded-[2rem] border border-nidan-gold/15 shadow-md">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div>
                            <label class="text-[10px] tracking-[0.2em] font-semibold uppercase text-nidan-text block mb-3">{{ BilingualService::label('rating') }}</label>
                            <div class="flex gap-2 flex-row-reverse justify-end">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="hidden peer" {{ $i === 5 ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="cursor-pointer text-2xl text-gray-300 peer-checked:text-nidan-gold hover:text-nidan-gold transition-colors">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] tracking-[0.2em] font-semibold uppercase text-nidan-text block mb-3">{{ BilingualService::label('your_thought') }}</label>
                            <textarea name="comment" rows="4" class="w-full bg-[#faf8f5] border border-gray-200 rounded-xl p-4 text-sm focus:bg-white focus:ring-2 focus:ring-nidan-gold focus:border-transparent transition-all resize-none text-nidan-text placeholder-gray-400" placeholder="Write your review here..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-nidan-text text-white rounded-full text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-nidan-gold transition-colors shadow-lg shadow-nidan-text/20">
                            {{ BilingualService::label('submit_review') }}
                        </button>
                    </form>
                @else
                    <div class="bg-white p-8 rounded-[2rem] border border-nidan-gold/15 shadow-md text-center">
                        <div class="w-14 h-14 bg-[#faf8f5] rounded-full flex items-center justify-center mx-auto mb-5 text-nidan-gold text-xl border border-nidan-gold/10">
                            <i class="fas fa-lock"></i>
                        </div>
                        <p class="text-sm text-gray-600 mb-8 font-light leading-relaxed">{{ BilingualService::label('signin_to_review') }}</p>
                        <a href="{{ route('login', ['locale' => app()->getLocale()]) }}" class="inline-block w-full py-4 bg-nidan-text text-white rounded-full text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-nidan-gold shadow-lg shadow-nidan-text/20 transition-colors">
                            {{ BilingualService::label('signin') }}
                        </a>
                    </div>
                @endauth
            </div>

            <div class="lg:col-span-2">
                <div class="space-y-8">
                    @forelse($product->reviews()->approved()->latest()->get() as $review)
                        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h4 class="font-serif text-xl text-nidan-text mb-2">{{ $review->name }}</h4>
                                    <div class="flex gap-1 text-[11px] text-nidan-gold">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-[10px] tracking-widest text-nidan-gold uppercase font-medium bg-[#faf8f5] border border-nidan-gold/10 px-4 py-1.5 rounded-full">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <p class="text-gray-600 text-[15px] leading-relaxed font-light">"{{ $review->comment }}"</p>
                        </div>
                    @empty
                        <div class="py-24 text-center border-2 border-dashed border-gray-200 rounded-[3rem] bg-white">
                            <div class="text-gray-300 text-5xl mb-6"><i class="far fa-comment-dots"></i></div>
                            <p class="text-gray-500 font-light text-lg">{{ BilingualService::label('no_reviews') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Thumbnail switching
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail-btn');
        
        thumbnails.forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.dataset.src;
                mainImage.src = src;
                
                // Update active state
                thumbnails.forEach(t => {
                    t.classList.remove('border-nidan-gold', 'active-thumb');
                    t.classList.add('border-transparent');
                });
                
                btn.classList.add('border-nidan-gold', 'active-thumb');
                btn.classList.remove('border-transparent');
            });
        });

        // Image Zoom Logic
        const zoomContainer = document.getElementById('zoom-container');
        if (zoomContainer && mainImage) {
            zoomContainer.addEventListener('mousemove', (e) => {
                const { left, top, width, height } = zoomContainer.getBoundingClientRect();
                const x = ((e.pageX - left - window.scrollX) / width) * 100;
                const y = ((e.pageY - top - window.scrollY) / height) * 100;
                
                mainImage.style.transformOrigin = `${x}% ${y}%`;
                mainImage.style.transform = 'scale(2)';
            });

            zoomContainer.addEventListener('mouseleave', () => {
                mainImage.style.transform = 'scale(1)';
                mainImage.style.transformOrigin = 'center center';
            });
        }

        // Quantity selector
        const qtyInput = document.getElementById('quantity-input');
        const qtyBtns = document.querySelectorAll('.quantity-btn');
        
        qtyBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                let val = parseInt(qtyInput.value);
                
                if (action === 'increase') {
                    val++;
                } else if (action === 'decrease' && val > 1) {
                    val--;
                }
                
                qtyInput.value = val;
            });
        });



        // Add to cart submission
        const cartForm = document.getElementById('add-to-cart-form');
        if (cartForm) {
            cartForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = cartForm.querySelector('button[type="submit"]');
                const btnSpan = btn.querySelector('span');
                const originalText = btnSpan.innerText;
                
                btnSpan.innerText = '{{ BilingualService::label("adding") ?? "Adding..." }}';
                btn.disabled = true;
                btn.classList.add('opacity-70');

                const formData = new FormData(cartForm);
                const url = cartForm.dataset.cartAddUrl;
                const redirectUrl = cartForm.dataset.cartRedirectUrl;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (result.success) {
                        document.getElementById('cart-sidebar-content').innerHTML = result.html;
                        if (typeof openCartSidebar === 'function') {
                            openCartSidebar();
                        } else {
                            // Fallback if global function isn't loaded
                            window.location.href = redirectUrl;
                        }
                        btnSpan.innerText = originalText;
                        btn.disabled = false;
                        btn.classList.remove('opacity-70');
                    } else {
                        alert(result.message || 'Something went wrong. Please try again.');
                        btnSpan.innerText = originalText;
                        btn.disabled = false;
                        btn.classList.remove('opacity-70');
                    }
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    alert('Error adding to cart.');
                    btnSpan.innerText = originalText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-70');
                }
            });
        }
    });
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : asset('logo.png') }}",
  "description": "{{ Str::limit($product->description, 200) }}",
  "sku": "{{ $product->sku ?? $product->id }}",
  "brand": {
    "@type": "Brand",
    "name": "ورد وهدايا المنوفية"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "EGP",
    "price": "{{ $product->selling_price }}",
    "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}",
    "seller": {
      "@type": "Organization",
      "name": "نيدان أتيليه"
    }
  }
  @if($product->approvedReviews()->count() > 0)
  ,"aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ round($product->approvedReviews()->avg('rating'), 1) }}",
    "reviewCount": "{{ $product->approvedReviews()->count() }}"
  }
  @endif
}
</script>
@endpush
