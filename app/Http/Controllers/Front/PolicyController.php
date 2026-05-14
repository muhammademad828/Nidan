<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PolicyController extends Controller
{
    public function show(string $locale, string $type): View
    {
        $policy = Policy::where('type', $type)->firstOrFail();
        return view('front.policies.show', compact('policy'));
    }
}
