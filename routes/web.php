<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;


// Routing Halaman Landing / Userhome (Fruitables)
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/shop', [LandingController::class, 'shop'])->name('landing.shop');
Route::get('/shop-detail', [LandingController::class, 'shopDetail'])->name('landing.shop-detail');
Route::get('/cart', [LandingController::class, 'cart'])->name('landing.cart');
Route::get('/checkout', [LandingController::class, 'checkout'])->name('landing.checkout');
Route::get('/contact', [LandingController::class, 'contact'])->name('landing.contact');

// Routing Halaman Admin Dashboard (Maxton)
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])
        ->middleware('throttle:5,1')
        ->name('admin.authenticate');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/modals', [AdminController::class, 'modals'])->name('admin.modals');
        Route::get('/forms', [AdminController::class, 'forms'])->name('admin.forms');
        Route::get('/datatable', [AdminController::class, 'datatable'])->name('admin.datatable');
        Route::get('/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    });
});