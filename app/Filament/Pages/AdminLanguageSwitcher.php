<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;

class AdminLanguageSwitcher extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.language-switcher';

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'تغيير اللغة' : 'Switch Language';
    }

    public function getTitle(): string
    {
        return app()->getLocale() === 'ar' ? 'لغة لوحة التحكم' : 'Panel Language';
    }

    public function switchTo(string $locale): void
    {
        if (! in_array($locale, ['ar', 'en'], true)) {
            return;
        }

        session(['admin_locale' => $locale]);
        \Illuminate\Support\Facades\App::setLocale($locale);

        Notification::make()
            ->title($locale === 'ar' ? 'تم تغيير اللغة إلى العربية' : 'Language switched to English')
            ->success()
            ->send();

        $this->redirect(request()->header('Referer') ?? '/admin');
    }
}
