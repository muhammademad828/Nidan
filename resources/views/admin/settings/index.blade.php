@extends('layouts.admin')

@section('title', 'Site Settings')
@section('page-title', 'Site Settings')
@section('breadcrumb', 'Home / Settings')

@section('page-content')
<div class="max-w-4xl">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 uppercase tracking-wider flex items-center gap-2">
                    <i class="fas {{ $group === 'footer' ? 'fa-shoe-prints' : ($group === 'social' ? 'fa-share-alt' : 'fa-info-circle') }} text-nidan-gold"></i>
                    {{ ucfirst($group) }} Settings
                </h3>

                <div class="space-y-8">
                    @foreach($groupSettings as $setting)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                                    {{ str_replace('_', ' ', $setting->key) }}
                                </label>
                            </div>
                            
                            <div>
                                <label class="block text-xs text-gray-500 mb-2">English Value</label>
                                @if(str_contains($setting->key, 'description') || str_contains($setting->key, 'address') || str_contains($setting->key, 'message') || str_contains($setting->key, 'text'))
                                    <textarea name="settings[{{ $setting->id }}][value_en]" rows="3" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold">{{ $setting->value_en }}</textarea>
                                @else
                                    <input type="text" name="settings[{{ $setting->id }}][value_en]" value="{{ $setting->value_en }}" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold">
                                @endif
                            </div>

                            <div dir="rtl">
                                <label class="block text-xs text-gray-500 mb-2 text-right">القيمة العربية</label>
                                @if(str_contains($setting->key, 'description') || str_contains($setting->key, 'address') || str_contains($setting->key, 'message') || str_contains($setting->key, 'text'))
                                    <textarea name="settings[{{ $setting->id }}][value_ar]" rows="3" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold text-right">{{ $setting->value_ar }}</textarea>
                                @else
                                    <input type="text" name="settings[{{ $setting->id }}][value_ar]" value="{{ $setting->value_ar }}" class="w-full border-gray-200 rounded-lg focus:ring-nidan-gold focus:border-nidan-gold text-right">
                                @endif
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="border-gray-50 my-6">
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-4 bg-nidan-gold text-white rounded-full font-bold shadow-lg hover:bg-[#b59660] transition-all transform hover:scale-105 active:scale-95">
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
