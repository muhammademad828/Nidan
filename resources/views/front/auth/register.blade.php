@extends('layouts.luxury')

@section('meta')
    <title>Register — Nidan Atelier</title>
@endsection

@section('content')
<nav class="bg-[#fbf9f3]/80 backdrop-blur-xl docked full-width top-0 sticky z-50 transition-opacity flex justify-between items-center w-full px-6 md:px-12 py-6 max-w-[1920px] mx-auto">
    <div class="flex items-center gap-12">
        <a href="{{ route('home') }}" class="text-2xl font-serif tracking-[0.2em] text-[#775a19]">NIDAN ATELIER</a>
    </div>
</nav>

<main class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl shadow-primary/5 p-10 border border-outline-variant/10">
        <div class="text-center mb-10">
            <h1 class="font-headline text-4xl text-on-background mb-4">Create Account</h1>
            <p class="text-sm text-secondary tracking-widest uppercase">Join the Nidan circle</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all"
                           placeholder="John Doe">
                    @error('name')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required 
                           class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all"
                           placeholder="01xxxxxxxxx">
                    @error('phone')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all"
                       placeholder="email@example.com">
                @error('email')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Gender</label>
                <div class="flex gap-4">
                    <label class="flex-1 flex items-center justify-center gap-2 py-4 border border-outline-variant/30 rounded-xl cursor-pointer hover:bg-primary/5 transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="gender" value="male" class="hidden" {{ old('gender') == 'male' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Male</span>
                    </label>
                    <label class="flex-1 flex items-center justify-center gap-2 py-4 border border-outline-variant/30 rounded-xl cursor-pointer hover:bg-primary/5 transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="gender" value="female" class="hidden" {{ old('gender') == 'female' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">Female</span>
                    </label>
                </div>
                @error('gender')
                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password-field" required 
                               class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all pr-12"
                               placeholder="••••••••">
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors focus:outline-none" 
                                onclick="togglePassword('password-field', 'eye-icon-1')">
                            <span id="eye-icon-1" class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="text-[10px] tracking-widest uppercase text-outline block mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password-confirmation-field" required 
                               class="w-full px-4 py-4 rounded-xl border border-outline-variant/30 focus:border-primary focus:ring-0 bg-surface-container-low transition-all pr-12"
                               placeholder="••••••••">
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors focus:outline-none" 
                                onclick="togglePassword('password-confirmation-field', 'eye-icon-2')">
                            <span id="eye-icon-2" class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:bg-on-primary-fixed-variant transition-all duration-300 font-label tracking-widest uppercase text-sm">
                Create Account
            </button>
        </form>

        <div class="mt-8 pt-8 border-t border-outline-variant/10 text-center">
            <p class="text-sm text-secondary">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline ml-1">Sign In</a>
            </p>
        </div>
    </div>
</main>

<footer class="bg-[#f5f3ee] py-12 text-center">
    <span class="text-[12px] font-['Manrope'] tracking-widest uppercase text-[#5f5e5e]">© {{ date('Y') }} NIDAN ATELIER. ALL RIGHTS RESERVED.</span>
</footer>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId, iconId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.textContent = 'visibility_off';
        } else {
            passwordField.type = 'password';
            eyeIcon.textContent = 'visibility';
        }
    }
</script>
@endpush
