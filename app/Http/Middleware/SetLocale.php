<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        // 1. URL prefix takes highest priority: /ar/... or /en/...
        $urlLocale = $request->segment(1);
        if (in_array($urlLocale, $this->supportedLocales)) {
            $this->applyLocale($urlLocale);
            return $next($request);
        }

        // 2. Session-persisted locale
        if ($locale = Session::get('locale')) {
            if (in_array($locale, $this->supportedLocales)) {
                $this->applyLocale($locale);
                return $next($request);
            }
        }

        // 3. Accept-Language header fallback
        $preferred = $request->getPreferredLanguage($this->supportedLocales);
        $this->applyLocale($preferred ?? 'en');

        return $next($request);
    }

    protected function applyLocale(string $locale): void
    {
        App::setLocale($locale);
        Session::put('locale', $locale);
    }
}
