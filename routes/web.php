<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProductBatchController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseInvoiceController;
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
    Route::get('/sales/invoices', [App\Http\Controllers\SalesController::class, 'invoiceList'])->name('sales.invoices');
    Route::get('/sales/invoices/{id}', [App\Http\Controllers\SalesController::class, 'invoiceDetail'])->name('sales.invoice-detail');

    Route::get('/returns', [App\Http\Controllers\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create', [App\Http\Controllers\ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns', [App\Http\Controllers\ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/{id}', [App\Http\Controllers\ReturnController::class, 'show'])->name('returns.show');

    Route::get('/returns/search-invoices', [App\Http\Controllers\ReturnController::class, 'searchInvoices'])->name('returns.search-invoices');
    Route::get('/returns/get-invoice-details/{id}', [App\Http\Controllers\ReturnController::class, 'getInvoiceDetails'])->name('returns.get-invoice-details');

    // Supplier routes
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');

    // Import routes
    Route::resource('imports', ImportController::class)->except(['edit', 'update', 'destroy']);
    Route::get('imports/{import}/payment-history', [ImportController::class, 'paymentHistory'])->name('imports.payment-history');
    Route::post('imports/{import}/update-payment', [ImportController::class, 'updatePayment'])->name('imports.update-payment');

    // Product Batch routes
    Route::post('product-batches', [ProductBatchController::class, 'store'])->name('product-batches.store');
    Route::get('product-batches/by-product/{productId}', [ProductBatchController::class, 'getByProduct'])->name('product-batches.by-product');

    // API routes for AJAX requests
    Route::get('api/products/search', [ProductController::class, 'search'])->name('api.products.search');
    Route::get('api/suppliers/search', [SupplierController::class, 'search'])->name('api.suppliers.search');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
