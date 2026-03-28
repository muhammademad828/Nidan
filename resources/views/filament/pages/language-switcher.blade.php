<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center py-12 gap-6">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ app()->getLocale() === 'ar' ? 'لغة لوحة التحكم' : 'Panel Language' }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'ar' ? 'اختر اللغة المفضلة للوحة التحكم' : 'Choose your preferred admin language' }}
        </p>
        <div class="flex gap-4">
            <x-filament::button
                wire:click="switchTo('ar')"
                color="{{ app()->getLocale() === 'ar' ? 'primary' : 'gray' }}"
                size="lg"
            >
                🇪🇬 العربية
            </x-filament::button>
            <x-filament::button
                wire:click="switchTo('en')"
                color="{{ app()->getLocale() === 'en' ? 'primary' : 'gray' }}"
                size="lg"
            >
                🇬🇧 English
            </x-filament::button>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'ar' ? 'اللغة الحالية:' : 'Current:' }}
            <strong>{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</strong>
        </p>
    </div>
</x-filament-panels::page>
