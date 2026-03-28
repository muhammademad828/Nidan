@extends('site.layout')

@push('head')
@if(Route::has('orders.custom.store'))
<meta name="bespoke-upload-url" content="{{ route('orders.custom.store') }}">
@else
<meta name="bespoke-upload-url" content="">
@endif
@if(Route::has('cart.add'))
<meta name="cart-add-url" content="{{ route('cart.add') }}">
@else
<meta name="cart-add-url" content="">
@endif
@endpush

@section('content')
@php
    $allowed = ['hero', 'services', 'best_sellers', 'wallets', 'jewelry', 'partners'];
@endphp
<main class="pt-32">
@if($sections->isEmpty())
@foreach($allowed as $componentName)
@if(View::exists('components.home.' . $componentName))
@include('components.home.' . $componentName)
@endif
@endforeach
@else
@foreach($sections as $section)
@if($section->is_visible && in_array($section->component_name, $allowed, true) && View::exists('components.home.' . $section->component_name))
@include('components.home.' . $section->component_name)
@endif
@endforeach
@endif
</main>
@include('components.home.whatsapp-fab')
@include('components.home.footer')
@endsection
