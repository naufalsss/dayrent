<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\DB;

// Halaman Utama Katalog Rental
Route::get('/', [HomeController::class, 'index'])->name('home');

// Default Dashboard bawaan Laravel Starter Kit -> Dioper otomatis ke beranda utama toko
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// BUBARKAN / HAPUS JALUR PROFIL BAWAAN LARAVEL BREEZE BIAR GAK BENTROK
// (Sudah dihapus rute profile.edit, profile.update, & profile.destroy bawaan)

require __DIR__.'/auth.php';

// =========================================================================
// GROUP 1: RUTE KHUSUS YANG BISA DIAKSES OLEH USER BIASA & ADMIN (Wajib Login)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Halaman Detail Checkout & Aksi Simpan Transaksi (Sudah Aman Capture user_id)
    Route::get('/items/{id}/checkout', [HomeController::class, 'checkout'])->name('items.checkout');
    Route::post('/items/{id}/checkout', [CheckoutController::class, 'storeCheckout'])->name('items.checkout.store');

    // Rute Riwayat Order / History Order milik user umum
    Route::get('/history-order', function () {
        // Mengambil riwayat milik user yang sedang login saat ini
        $myHistory = DB::table('rentals')
            ->join('items', 'rentals.item_id', '=', 'items.id')
            ->select('rentals.*', 'items.name as item_name', 'items.price as item_price', 'items.image as item_image')
            ->where('rentals.user_id', auth()->id())
            ->orderBy('rentals.created_at', 'desc')
            ->get();

        return view('history_order', compact('myHistory'));
    })->name('history.order');
    
    // Rute Manajemen Kelengkapan Akun & Password Mandiri User (1 Halaman untuk Semua)
    Route::get('/profile/complete', [ProfileController::class, 'completeAccountView'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'updateCompleteAccount'])->name('profile.complete.update');

    // Jalur pengiriman bintang rating dari pop-up user
    Route::post('/notifications/{id}/rate', [App\Http\Controllers\NotificationController::class, 'submitRating'])->name('notifications.rate');
});


// =========================================================================
// GROUP 2: RUTE PROFEKSIONAL BACKEND ADMIN (Wajib Login DAN Wajib Role Admin)
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

    // MANAGEMENT TRANSAKSI PENYEWAAN ADMIN (Diproteksi Satpam Admin)
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals/approve/{id}', [RentalController::class, 'approve'])->name('rentals.approve');
    Route::post('/rentals/decline/{id}', [RentalController::class, 'decline'])->name('rentals.decline');
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->name('rentals.destroy');

});