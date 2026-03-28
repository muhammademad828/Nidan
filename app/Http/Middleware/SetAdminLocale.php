<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    protected array $supportedLocales = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('admin_locale', 'ar');

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = 'ar';
        }

        App::setLocale($locale);

        // Ensure the Filament direction hint is available in Blade
        app()->singleton('filament.admin_direction', fn () => $locale === 'ar' ? 'rtl' : 'ltr');

        return $next($request);
    }
}
