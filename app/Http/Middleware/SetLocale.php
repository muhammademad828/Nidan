<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');
        $resolvedLocale = in_array($locale, config('bilingual.locales', []), true)
            ? $locale
            : config('bilingual.default');

        App::setLocale($resolvedLocale);
        URL::defaults(['locale' => $resolvedLocale]);

        return $next($request);
    }
}
