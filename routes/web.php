<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Warung Pedesan — Web Routes
|--------------------------------------------------------------------------
*/

// ── Auth (Guest Only) ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Admin ─────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Menu CRUD (tanpa 'show', karena tidak ada halaman detail menu)
            Route::resource('menus', MenuController::class)->except(['show']);

            // Laporan penjualan + chart pendapatan bulanan
            Route::get('/laporan', [TransaksiController::class, 'adminIndex'])->name('laporan');

            // Export laporan ke PDF (mPDF), termasuk chart & tabel
            Route::get('/laporan/export', [TransaksiController::class, 'exportLaporan'])->name('laporan.export');
        });

    // ── Kasir ─────────────────────────────────────────────────────────────────
    Route::middleware('role:kasir')
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {

            Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
            Route::get('/transaksi/baru', [TransaksiController::class, 'create'])->name('transaksi.create');
            Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
            Route::patch('/transaksi/{id}/bayar', [TransaksiController::class, 'updatePaymentStatus'])->name('transaksi.bayar');
        });
});
