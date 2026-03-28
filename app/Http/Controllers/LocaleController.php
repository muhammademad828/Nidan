<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $supported = ['en', 'ar'];

        if (in_array($locale, $supported)) {
            Session::put('locale', $locale);
        }

        return back();
    }
}
