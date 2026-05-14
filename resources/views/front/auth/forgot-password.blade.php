@extends('layouts.luxury')

@section('meta')
    <title>Forgot Password — Nidan Atelier</title>
@endsection

@section('content')
<nav class="bg-[#fbf9f3]/80 backdrop-blur-xl docked full-width top-0 sticky z-50 transition-opacity flex justify-between items-center w-full px-6 md:px-12 py-6 max-w-[1920px] mx-auto">
    <div class="flex items-center gap-12">
        <a href="{{ route('home') }}" class="text-2xl font-serif tracking-[0.2em] text-[#775a19]">NIDAN ATELIER</a>
    </div>
</nav>

<main class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-primary/5 p-10 border border-outline-variant/10">
        <div class="text-center mb-10">
            <h1 class="font-headline text-4xl text-on-background mb-4">{{ app()->getLocale() == 'ar' ? 'نسيت كلمة السر' : 'Forgot Password' }}</h1>
            <p class="text-sm text-secondary tracking-widest uppercase">
                {{ app()->getLocale() == 'ar' ? 'أدخل بياناتك لاستعادة الوصول' : 'Provide your details to regain access' }}
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-error/10 text-error text-sm rounded-lg border border-error/20 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all"
                       placeholder="email@example.com">
                @error('email')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required 
                       class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all"
                       placeholder="01xxxxxxxxx">
                @error('phone')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:bg-on-primary-fixed-variant transition-all duration-300 font-label tracking-widest uppercase text-sm">
                {{ app()->getLocale() == 'ar' ? 'تحقق من البيانات' : 'Verify Identity' }}
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-outline-variant/10 text-center">
            <p class="text-sm text-secondary">
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">
                    {{ app()->getLocale() == 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Login' }}
                </a>
            </p>
        </div>
    </div>
</main>

<footer class="bg-[#f5f3ee] py-12 text-center">
    <span class="text-[12px] font-['Manrope'] tracking-widest uppercase text-[#5f5e5e]">© {{ date('Y') }} NIDAN ATELIER. ALL RIGHTS RESERVED.</span>
</footer>
@endsection
