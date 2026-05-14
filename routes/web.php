<?php

use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrderTrackingController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\Front\PolicyController as FrontPolicyController;
use App\Http\Controllers\Front\ReviewController as FrontReviewController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\SitemapController;
use App\Services\BilingualService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to default locale
Route::redirect('/', '/en');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/products/{slug}', function (string $slug) {
    return redirect()->route('product.show', [
        'locale' => config('bilingual.default', 'en'),
        'slug' => $slug,
    ]);
})->name('product.show.legacy');

// Bilingual route group: /en/... and /ar/...
Route::get('/debug-slides', [HomeController::class, 'testDebug']);

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|ar'],
    'middleware' => 'locale'
], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/search', [SearchController::class, 'index'])->name('search');
        Route::get('/collections', [FrontCategoryController::class, 'index'])->name('collections');
        Route::get('/collections/{category:slug}', [FrontCategoryController::class, 'show'])->name('category.show');

        Route::get('/products/{product:slug}', [FrontProductController::class, 'show'])->name('product.show');

        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{index}/{delta}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{index}', [CartController::class, 'remove'])->name('cart.remove');

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->middleware('throttle:checkout-submit')
            ->name('checkout.store');

        Route::get('/track', [OrderTrackingController::class, 'index'])->name('track');
        Route::post('/track', [OrderTrackingController::class, 'track'])
            ->middleware('throttle:track-submit')
            ->name('track.search');

        Route::get('/policies/{type}', [FrontPolicyController::class, 'show'])->name('policy');
        Route::get('/pages/{slug}', [\App\Http\Controllers\Front\PageController::class, 'show'])->name('page.show');

        Route::post('/reviews', [FrontReviewController::class, 'store'])
            ->middleware('auth')
            ->name('reviews.store');

        Route::get('/lang/switch/{targetLocale}', [HomeController::class, 'switchLang'])->name('lang.switch');

        Route::get('/locations/cities/{governorate}', function ($locale, \App\Models\Governorate $governorate) {
            return response()->json($governorate->cities()->get(['id', 'name_en', 'name_ar', 'shipping_price']));
        })->name('locations.cities');

        // User Auth
        Route::middleware('guest')->group(function () {
            Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/login', [AuthController::class, 'login']);
            Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
            Route::post('/register', [AuthController::class, 'register']);
            
            // Custom Forgot Password (Email + Phone)
            Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
            Route::post('/forgot-password', [AuthController::class, 'verifyForgotPassword'])->name('password.email');
            Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
            Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
        });

        Route::middleware('auth')->group(function() {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/account', [AuthController::class, 'account'])->name('account');
            Route::post('/account/password', [AuthController::class, 'updatePassword'])->name('password.change');
        });
    });