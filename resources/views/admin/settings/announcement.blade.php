@extends('layouts.admin')

@section('title', 'Announcement Bar')
@section('page-title', 'Announcement Bar')
@section('breadcrumb', 'Home / Settings / Announcement Bar')

@section('page-content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 bg-nidan-gold rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-bullhorn"></i>
                </div>
                Manage Announcement Bar
            </h3>
            <p class="text-gray-500 mt-2 text-sm">Update the scrolling text that appears at the very top of your store.</p>
        </div>

        <form action="{{ route('admin.settings.announcement.update') }}" method="POST" class="p-8">
            @csrf
            
            <div class="space-y-8">
                <!-- English Content -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 bg-nidan-gold rounded-full"></span>
                            English Announcement
                        </label>
                        <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded-md font-bold uppercase">English</span>
                    </div>
                    <textarea 
                        name="value_en" 
                        rows="4" 
                        class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold transition-all text-gray-600 placeholder:text-gray-300"
                        placeholder="Enter the announcement in English..."
                    >{{ old('value_en', $setting->value_en ?? '') }}</textarea>
                    <p class="text-[11px] text-gray-400 italic">This will appear when the site language is English.</p>
                </div>

                <div class="border-t border-gray-50"></div>

                <!-- Arabic Content -->
                <div class="space-y-3" dir="rtl">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 bg-nidan-gold rounded-full"></span>
                            الإعلان باللغة العربية
                        </label>
                        <span class="text-[10px] bg-green-50 text-green-600 px-2 py-1 rounded-md font-bold uppercase">العربية</span>
                    </div>
                    <textarea 
                        name="value_ar" 
                        rows="4" 
                        class="w-full border-gray-200 rounded-xl focus:ring-nidan-gold focus:border-nidan-gold transition-all text-gray-600 placeholder:text-gray-300 text-right font-sans"
                        placeholder="أدخل نص الشريط باللغة العربية..."
                    >{{ old('value_ar', $setting->value_ar ?? '') }}</textarea>
                    <p class="text-[11px] text-gray-400 italic text-right">سيظهر هذا النص عندما تكون لغة الموقع هي العربية.</p>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2 text-amber-600 bg-amber-50 px-4 py-2 rounded-lg text-xs">
                    <i class="fas fa-info-circle"></i>
                    <span>Use "—" or "•" to separate phrases for a better scrolling look.</span>
                </div>
                <button type="submit" class="px-8 py-3 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105 active:scale-95 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Preview -->
    <div class="mt-8 bg-nidan-dark rounded-xl p-6 border border-white/5">
        <h4 class="text-white/50 text-[10px] uppercase tracking-widest mb-4 font-bold">Live Preview (Mockup)</h4>
        <div class="w-full bg-[#1b1c19] text-nidan-gold py-2 overflow-hidden whitespace-nowrap border-b border-nidan-gold/20 rounded">
            <div class="animate-marquee text-[10px] tracking-[0.3em] uppercase flex items-center opacity-80">
                <span class="px-4" id="preview_en">{{ $setting->value_en ?? 'Announcement Preview...' }}</span>
                <span class="px-4" id="preview_en2">{{ $setting->value_en ?? 'Announcement Preview...' }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const inputEn = document.querySelector('textarea[name="value_en"]');
    const previewEn = document.getElementById('preview_en');
    const previewEn2 = document.getElementById('preview_en2');

    inputEn.addEventListener('input', (e) => {
        previewEn.textContent = e.target.value || 'Announcement Preview...';
        previewEn2.textContent = e.target.value || 'Announcement Preview...';
    });
</script>
@endpush
@endsection
