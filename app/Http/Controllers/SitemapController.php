<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::active()->get(['id', 'slug', 'updated_at']);
        $categories = \App\Models\Category::active()->get(['id', 'slug', 'updated_at']);

        return response()->view('sitemap', [
            'products' => $products,
            'categories' => $categories,
        ])->header('Content-Type', 'application/xml');
    }
}