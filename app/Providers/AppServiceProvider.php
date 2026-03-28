<?php

namespace App\Providers;

use App\View\SiteSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        /*
         * Global $siteSettings for Blade (equivalent to view()->share on each relevant render).
         * Values are cached (SiteSettings::CACHE_KEY); CmsService::invalidateForeverCaches() runs on SiteSetting save.
         */
        View::composer('*', function ($view) {
            $name = (string) $view->name();
            if (str_starts_with($name, 'filament.')) {
                return;
            }
            if (str_starts_with($name, 'livewire::')) {
                return;
            }
            if (str_starts_with($name, 'mail::') || str_starts_with($name, 'emails.')) {
                return;
            }
            if (! $view->offsetExists('siteSettings')) {
                $view->with('siteSettings', SiteSettings::resolveCached());
            }
        });

        View::composer('site.layout', function ($view) {
            $user = request()->user();
            $view->with(
                'visualEditorEnabled',
                (bool) ($user && $user->is_admin)
            );
        });

        View::composer('app', function ($view) {
            $user = request()->user();
            $path = request()->path();
            $view->with(
                'visualEditorEnabled',
                (bool) ($user && $user->is_admin && ! str_starts_with($path, 'dashboard'))
            );
        });
    }
}
