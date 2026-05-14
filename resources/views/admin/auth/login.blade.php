<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — Nidan Atelier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background: #F9F6EE;">
    <div class="w-full max-w-md mx-4">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: #D4B87E;">
                <span class="text-white font-serif text-3xl font-bold">N</span>
            </div>
            <h1 class="text-3xl font-serif" style="color: #2D2319;">Nidan Atelier</h1>
            <p class="text-sm mt-2 tracking-widest uppercase" style="color: #D4B87E;">Admin Portal</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8" style="border: 1px solid rgba(212,184,126,0.2);">
            @if(session('error'))
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs uppercase tracking-widest mb-2" style="color: #2D2319;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#D4B87E] transition-colors"
                            style="border-color: rgba(212,184,126,0.3); background: #FDFAF5;"
                            placeholder="admin@nidanatelier.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-widest mb-2" style="color: #2D2319;">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[#D4B87E] transition-colors"
                            style="border-color: rgba(212,184,126,0.3); background: #FDFAF5;"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border-gray-300 text-[#D4B87E] focus:ring-[#D4B87E]">
                        <label for="remember" class="ml-2 block text-xs text-gray-700 uppercase tracking-widest cursor-pointer" style="color: #2D2319;">Remember Me</label>
                    </div>
                    <button type="submit"
                        class="w-full py-3 rounded-full text-white font-medium text-sm uppercase tracking-widest transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4B87E]"
                        style="background: #C5A165;">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs mt-6" style="color: rgba(45,35,25,0.4);">
            &copy; {{ date('Y') }} Nidan Atelier. All rights reserved.
        </p>
    </div>
</body>
</html>
