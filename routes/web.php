<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Api\FrontendApiController;
use App\Http\Controllers\Admin\ShippingController as AdminShippingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\OtpController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\BespokeUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| NIDAN Storefront Routes
|--------------------------------------------------------------------------
*/

// Language & Region
Route::post('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::post('/region', [LocationController::class, 'setRegion'])->name('region.set');

// Storefront
Route::get('/', HomeController::class)->name('home');

Route::post('/api/frontend/update', [FrontendApiController::class, 'update'])
    ->middleware(['auth', EnsureIsAdmin::class, 'throttle:120,1'])
    ->name('api.frontend.update');

Route::post('/orders/custom', [BespokeUploadController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('orders.custom.store');

Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',              [CartController::class, 'get'])->name('get');
    Route::post('/add',          [CartController::class, 'add'])->name('add');
    Route::patch('/update/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/bundle',       [CartController::class, 'addBundle'])->name('add-bundle');
});

// Search
Route::get('/api/search', SearchController::class)->name('search');

// Cities by governorate (for checkout dependent dropdown)
Route::get('/api/governorates/{governorate}/cities', function (\App\Models\Governorate $governorate) {
    return response()->json(
        $governorate->activeCities()->get()->map(fn ($c) => [
            'id'           => $c->id,
            'name_ar'      => $c->name_ar,
            'name_en'      => $c->name_en,
            'delivery_fee' => (float) $c->delivery_fee,
        ])
    );
})->name('api.cities');

// Newsletter
Route::post('/newsletter', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/',       [CheckoutController::class, 'index'])->name('index');
    Route::post('/',      [CheckoutController::class, 'store'])->name('store');
    Route::get('/success', [OrderController::class, 'success'])->name('success');
});

/*
|--------------------------------------------------------------------------
| Customer Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');

    // Password Reset via OTP
    Route::get('/forgot-password', [OtpController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [OtpController::class, 'sendOtp'])->name('password.email');
    Route::get('/reset-password', [OtpController::class, 'showVerifyOtp'])->name('password.verify');
    Route::post('/reset-password', [OtpController::class, 'verifyAndReset'])->name('password.update');
    // Direct password reset via signed URL (bypasses OTP email)
    Route::get('/reset-password/direct/{email}', [OtpController::class, 'showDirectReset'])->name('password.reset.direct');
    Route::post('/reset-password/direct', [OtpController::class, 'processDirectReset'])->name('password.reset.process');
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('customer.logout');

/*
|--------------------------------------------------------------------------
| Customer Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('addresses.destroy');
});

/*
|--------------------------------------------------------------------------
| Legacy Filament redirects — send /admin/* to our custom /dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin', fn() => redirect('/dashboard', 301))->name('admin.home.redirect');
Route::get('/admin/{any}', fn() => redirect('/dashboard', 301))
    ->where('any', '.*')
    ->name('admin.catch.redirect');

/*
|--------------------------------------------------------------------------
| NIDAN Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(EnsureIsAdmin::class)->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/', AdminDashboardController::class)->name('admin.dashboard');

        Route::prefix('settings')->name('admin.settings.')->group(function () {
            Route::get('/',    [AdminSettingsController::class, 'index'])->name('index');
            Route::put('/',    [AdminSettingsController::class, 'update'])->name('update');
            Route::post('/upload-image', [AdminSettingsController::class, 'uploadImage'])->name('upload-image');
        });

        Route::prefix('products')->name('admin.products.')->group(function () {
            Route::get('/',           [AdminProductController::class, 'index'])->name('index');
            Route::get('/create',     [AdminProductController::class, 'create'])->name('create');
            Route::post('/',          [AdminProductController::class, 'store'])->name('store');
            Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
            Route::put('/{product}',  [AdminProductController::class, 'update'])->name('update');
            Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/',          [AdminOrderController::class, 'index'])->name('index');
            Route::get('/{order}',   [AdminOrderController::class, 'show'])->name('show');
            Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
        });

        Route::prefix('shipping')->name('admin.shipping.')->group(function () {
            Route::get('/',                                          [AdminShippingController::class, 'index'])->name('index');
            Route::patch('/cities/{city}',                          [AdminShippingController::class, 'updateCity'])->name('city.update');
            Route::patch('/governorates/{governorate}/bulk',        [AdminShippingController::class, 'bulkUpdateGovernorate'])->name('governorate.bulk');
            Route::patch('/governorates/{governorate}/toggle',      [AdminShippingController::class, 'toggleGovernorate'])->name('governorate.toggle');
        });

        // Unread orders count (polling)
        Route::get('/notifications/unread-count', [AdminOrderController::class, 'unreadCount'])->name('admin.notifications.unread');
    });
});
