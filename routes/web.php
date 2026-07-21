<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Merchant\MerchantDashboardController;
use App\Http\Controllers\Merchant\MerchantItemController;
use App\Http\Controllers\Admin\MerchantApprovalController;

// Halaman Utama Publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Jalur Rute Halaman Katalog Unit Publik
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

Route::get('/guide', function () {
    $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();
    return view('guide', compact('configs'));
})->name('guide');

// Jalur Rute Pusat Bantuan (Help) Publik Baru dengan Sinkronisasi DB cms_configs
Route::get('/help', function () {
    $configs = DB::table('cms_configs')->pluck('value', 'key')->toArray();
    return view('help', compact('configs'));
})->name('help');

// Default Dashboard bawaan Laravel Starter Kit -> Dioper otomatis ke beranda utama toko
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Auth Routes dari Laravel Breeze
require __DIR__.'/auth.php';

// =========================================================================
// GROUP 1: RUTE KHUSUS YANG BISA DIAKSES OLEH USER BIASA & ADMIN (Wajib Login)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Detail Checkout & Aksi Simpan Transaksi
    Route::get('/items/{id}/checkout', [HomeController::class, 'checkout'])->name('items.checkout');
    Route::post('/items/{id}/checkout', [CheckoutController::class, 'storeCheckout'])->name('items.checkout.store');

    Route::post('/checkout/apply-promo', [App\Http\Controllers\CheckoutController::class, 'applyPromo'])->name('checkout.applyPromo');

    // Rute Riwayat Order / History Order milik user umum
    // FIX LIVE JALUR GAMBAR: Memastikan data history order menarik alias item_image dengan aman
    Route::get('/history-order', function () {
        $myHistory = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.price as item_price', 'items.image as item_image')
            ->where('rentals.user_id', auth()->id())
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('history_order', compact('myHistory'));
    })->name('history.order');
    
    // Rute Manajemen Kelengkapan Akun & Password Mandiri User
    Route::get('/profile/complete', [ProfileController::class, 'completeAccountView'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'updateCompleteAccount'])->name('profile.complete.update');

    // Jalur pengiriman bintang rating dari pop-up user
    Route::post('/notifications/{id}/rate', [App\Http\Controllers\NotificationController::class, 'submitRating'])->name('notifications.rate');
    
    // Jalur menutup / abaikan notifikasi rating
    Route::post('/notifications/{id}/dismiss', [App\Http\Controllers\NotificationController::class, 'dismissRating'])->name('notifications.dismiss');

    Route::get('/order-details/{id}', function ($id) {
        $order = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->join('users', 'rentals.user_id', '=', 'users.id')
            ->select('rentals.*', 'items.name as item_name', 'items.price as item_price', 'items.image as item_image', 'users.email as user_email')
            ->where('rentals.id', $id)
            ->where('rentals.user_id', auth()->id())
            ->first();

        if (!$order) { return redirect('/history-order'); }

        return view('order_details', compact('order'));
    })->name('order.details');

});

// =========================================================================
// GROUP 2: RUTE PROFESIONAL BACKEND ADMIN (Wajib Login DAN Wajib Role Admin)
// =========================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Alamat: dayrent.test/admin/dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Alamat: dayrent.test/admin/cms
    Route::get('/cms', [DashboardController::class, 'cmsSettings'])->name('cms');
    Route::post('/cms/update', [DashboardController::class, 'updateCms'])->name('cms.update');

    Route::resource('categories', CategoryController::class);

    Route::resource('items', ItemController::class);

    // Route Tambahan untuk Manajemen Smart Slider Promo
    Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::put('/promos/{id}', [PromoController::class, 'update'])->name('promos.update');
    Route::delete('/promos/{id}', [PromoController::class, 'destroy'])->name('promos.destroy');

    // Route Manajemen Akun Admin/User
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'store' => 'users.store',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ])->except(['create', 'edit', 'show']);

    // MANAGEMENT STOK BARANG JALUR KUSTOM
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/update/{id}', [StockController::class, 'updateStock'])->name('stock.update');
    Route::post('/stock/delete/{id}', [StockController::class, 'deleteStock'])->name('stock.delete');

    // MANAGEMENT TRANSAKSI PENYEWAAN ADMIN
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals/approve/{id}', [RentalController::class, 'approve'])->name('rentals.approve');
    Route::post('/rentals/decline/{id}', [RentalController::class, 'decline'])->name('rentals.decline');
    
    Route::post('/rentals/confirm-return/{id}', [RentalController::class, 'confirmReturn'])->name('rentals.confirmReturn');
    
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('rentals.destroy');

    // Route CRUD Manajemen Kode Promo Baru
    Route::resource('promo-codes', App\Http\Controllers\Admin\PromoCodeController::class)->names([
        'index'   => 'promo-codes.index',
        'store'   => 'promo-codes.store',
        'destroy' => 'promo-codes.destroy',
    ])->except(['create', 'edit', 'show', 'update']);

    // Rute Merchant Approval
    Route::get('/merchant-approval', [MerchantApprovalController::class, 'index'])->name('merchant-approval.index');
    Route::post('/merchant-approval/{id}/approve', [MerchantApprovalController::class, 'approve'])->name('merchant-approval.approve');
    Route::post('/merchant-approval/{id}/decline', [MerchantApprovalController::class, 'decline'])->name('merchant-approval.decline');
});

// =========================================================================
// GROUP 3: RUTE BACKEND MERCHANT (Wajib Login DAN Wajib Role Merchant)
// =========================================================================
Route::middleware(['auth', 'merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    
    // Alamat: dayrent.test/merchant/dashboard
    Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('dashboard');
    
    // Data Transaksi Penyewaan Khusus Unit Milik Merchant
    Route::get('/rentals', [MerchantDashboardController::class, 'rentals'])->name('rentals.index');
    
    // =========================================================================
    // FIX UTAMA: MENAMBAHKAN RUTE APPROVE & DECLINE UNTUK MERCHANT DASHBOARD
    // =========================================================================
    Route::post('/rentals/approve/{id}', [MerchantDashboardController::class, 'approve'])->name('rentals.approve');
    Route::post('/rentals/decline/{id}', [MerchantDashboardController::class, 'decline'])->name('rentals.decline');
    
    Route::post('/rentals/confirm-return/{id}', [MerchantDashboardController::class, 'markAsReturned'])->name('rentals.returned');

    // CRUD Unit Barang Mandiri khusus Merchant (Terisolasi hanya mengolah barang milik ID sendiri)
    Route::get('/items', [MerchantItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [MerchantItemController::class, 'create'])->name('items.create');
    Route::post('/items', [MerchantItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [MerchantItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [MerchantItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [MerchantItemController::class, 'destroy'])->name('items.destroy');

    // Manajemen Stok Merchant
    Route::get('/stock', [MerchantDashboardController::class, 'stock'])->name('stock.index');
    Route::post('/stock/update/{id}', [MerchantDashboardController::class, 'updateStock'])->name('stock.update');
});