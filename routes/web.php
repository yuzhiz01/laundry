<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// Public landing & tracking routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tracking', [HomeController::class, 'track'])->name('tracking');

// Auth routes (Petugas)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile settings
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('outlet', OutletController::class);
        Route::resource('user', UserController::class);
        Route::resource('paket', PaketController::class);
    });

    // Admin dan Kasir
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('member', MemberController::class);

        Route::get('/transaksi/paket-by-outlet/{outletId}', [TransaksiController::class, 'getPaketByOutlet'])->name('transaksi.paket_by_outlet');
        Route::resource('transaksi', TransaksiController::class);
        Route::patch('/transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.update_status');
        Route::patch('/transaksi/{transaksi}/bayar', [TransaksiController::class, 'updateBayar'])->name('transaksi.update_bayar');
        Route::get('/transaksi/{transaksi}/cetak-invoice', [TransaksiController::class, 'cetakInvoice'])->name('transaksi.cetak_invoice');
        Route::get('/transaksi/{transaksi}/cetak-struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak_struk');
    });

    // Admin, Kasir, dan Owner
    Route::middleware('role:admin,kasir,owner')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });
});
