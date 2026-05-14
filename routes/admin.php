<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AddOnController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

// Public admin routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected admin routes
Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/mark-read', [DashboardController::class, 'markNotifsRead'])->name('notifications.markRead');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Vendors
    Route::resource('vendors', VendorController::class)->except(['show']);

    // Products
    Route::resource('products', ProductController::class)->parameters([
        'products' => 'product:id'
    ]);

    // Add-ons
    Route::resource('addons', AddOnController::class)->except(['show']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Custom Orders
    Route::get('/custom-orders/create', [CustomOrderController::class, 'create'])->name('custom-orders.create');
    Route::post('/custom-orders', [CustomOrderController::class, 'store'])->name('custom-orders.store');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::patch('/reviews/{review}/hide', [ReviewController::class, 'hide'])->name('reviews.hide');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Policies
    Route::get('/policies/terms', [PolicyController::class, 'editTerms'])->name('policies.terms');
    Route::resource('policies', PolicyController::class)->only(['index', 'edit', 'update']);
    Route::resource('pages', PageController::class);

    // Hero Slides
    Route::post('slides/reorder', [\App\Http\Controllers\Admin\HeroSlideController::class, 'reorder'])->name('slides.reorder');
    Route::resource('slides', \App\Http\Controllers\Admin\HeroSlideController::class);

    // Announcement Bar
    Route::get('/settings/announcement', [\App\Http\Controllers\Admin\SiteSettingController::class, 'announcement'])->name('settings.announcement');
    Route::post('/settings/announcement', [\App\Http\Controllers\Admin\SiteSettingController::class, 'updateAnnouncement'])->name('settings.announcement.update');

    // Shipping Management
    Route::get('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::patch('/shipping/{city}', [\App\Http\Controllers\Admin\ShippingController::class, 'updatePrice'])->name('shipping.update');
    Route::post('/shipping/city', [\App\Http\Controllers\Admin\ShippingController::class, 'storeCity'])->name('shipping.store');
    Route::delete('/shipping/{city}', [\App\Http\Controllers\Admin\ShippingController::class, 'destroyCity'])->name('shipping.destroy');

    // Site Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

    // Home Page Dynamic Sections
    Route::post('home-sections/reorder', [\App\Http\Controllers\Admin\HomeSectionController::class, 'reorder'])->name('home-sections.reorder');
    Route::resource('home-sections', \App\Http\Controllers\Admin\HomeSectionController::class);
    Route::post('home-sections/{homeSection}/toggle', [\App\Http\Controllers\Admin\HomeSectionController::class, 'toggle'])->name('home-sections.toggle');
    
    // Tags
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->only(['index', 'store', 'destroy']);

    // Event Slider
    Route::post('event-slides/reorder', [\App\Http\Controllers\Admin\EventSlideController::class, 'reorder'])->name('event-slides.reorder');
    Route::resource('event-slides', \App\Http\Controllers\Admin\EventSlideController::class);

    // Customer Intelligence
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
});