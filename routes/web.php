<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::resource('users', UserController::class);

    Route::resource('products', ProductController::class);
    Route::patch('/products/{product}/deactivate', [ProductController::class, 'deactivate'])->name('products.deactivate');
    Route::patch('/products/{product}/activate', [ProductController::class, 'activate'])->name('products.activate');

    Route::resource('product-categories', ProductCategoryController::class);

    // Sales routes
    Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/search-products', [App\Http\Controllers\SalesController::class, 'searchProducts'])->name('sales.search-products');
    Route::get('/sales/get-product/{id}', [App\Http\Controllers\SalesController::class, 'getProduct'])->name('sales.get-product');
    Route::post('/sales/create-invoice', [App\Http\Controllers\SalesController::class, 'createInvoice'])->name('sales.create-invoice');

    Route::resource('purchases', PurchaseController::class);
    Route::resource('suppliers', SupplierController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
