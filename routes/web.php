<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MakananController;
use App\Http\Controllers\StokGasController;
use App\Http\Controllers\TipeGasController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MutasiGasController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianGasController;
use App\Http\Controllers\PenjualanGasController;
use App\Http\Controllers\TransaksiGasController;
use App\Http\Controllers\PengembalianGasController;

// Auth Routes
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // API endpoint for real-time stats
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getRealtimeStats'])->name('dashboard.stats');

    // Stock management
    Route::resource('/stok', StokGasController::class);
    // Route::post('stok/adjustment', [StokGasController::class, 'adjustment'])->name('stok.adjustment');

    // Transactions
    // Route::get('transaksi/penjualan', [PenjualanGasController::class, 'index'])->name('transaksi.penjualan.index');
    // Route::post('transaksi/penjualan/store', [PenjualanGasController::class, 'store'])->name('transaksi.penjualan.store');

    Route::get('transaksi/pengembalian', [PengembalianGasController::class, 'index'])->name('transaksi.pengembalian.index');
    Route::post('transaksi/pengembalian/store', [PengembalianGasController::class, 'store'])->name('transaksi.pengembalian.store');

    Route::get('transaksi/pembelian', [PembelianGasController::class, 'index'])->name('transaksi.pembelian.index');
    Route::post('transaksi/pembelian/store', [PembelianGasController::class, 'store'])->name('transaksi.pembelian.store');
    Route::get('transaksi/pembelian/struk/{kodePembelian}', [PembelianGasController::class, 'printStruk'])->name('transaksi.pembelian.struk');

    Route::get('transaksi/penjualan', [TransaksiGasController::class, 'index'])->name('transaksi.penjualan.index');
    Route::post('transaksi/penjualan/store', [TransaksiGasController::class, 'store'])->name('transaksi.penjualan.store');
    Route::get('transaksi/penjualan/struk/{kodeTransaksi}', [TransaksiGasController::class, 'printStruk'])->name('transaksi.penjualan.struk');

    // Reports - Using dedicated LaporanController
    Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('/laporan/mingguan', [LaporanController::class, 'mingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');

    // PDF Export Routes
    Route::get('/laporan/export/harian', [LaporanController::class, 'exportHarianPdf'])->name('laporan.export.harian');
    Route::get('/laporan/export/mingguan', [LaporanController::class, 'exportMingguanPdf'])->name('laporan.export.mingguan');
    Route::get('/laporan/export/bulanan', [LaporanController::class, 'exportBulananPdf'])->name('laporan.export.bulanan');

    // Admin & Owner only routes
    Route::middleware('role:super-admin,owner')->group(function () {
        // Employee Management
        Route::get('/manajemen-user/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/manajemen-user/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/manajemen-user/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/manajemen-user/karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/manajemen-user/karyawan/update', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('/manajemen-user/karyawan/{id}/show', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::delete('/manajemen-user/karyawan/{id}/delete', [KaryawanController::class, 'destroy'])->name('karyawan.delete');

        
        // Vendor Management
        Route::get('/manajemen-user/vendor', [VendorController::class, 'index'])->name('vendor.index');
        Route::get('/manajemen-user/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
        Route::post('/manajemen-user/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
        Route::get('/manajemen-user/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
        Route::post('/manajemen-user/vendor/update', [VendorController::class, 'update'])->name('vendor.update');
        Route::get('/manajemen-user/vendor/{id}/show', [VendorController::class, 'show'])->name('vendor.show');
        Route::delete('/manajemen-user/vendor/{id}/delete', [VendorController::class, 'destroy'])->name('vendor.delete');
        
        // Pelanggan Management
        Route::resource('/manajemen-user/pelanggan', PelangganController::class)->names('pelanggan');

        // Gas Types
        Route::resource('/master-data/tipe-gas', TipeGasController::class)->names('tipe-gas');

        Route::resource('/barangs', BarangController::class)->names('barangs');
        
        Route::resource('/makanans', MakananController::class)->names('makanans');

        
        
        // Mutations
        Route::get('/mutasi', [MutasiGasController::class, 'index'])->name('mutasi.index');
        Route::post('/mutasi/export-excel', [MutasiGasController::class, 'exportExcel'])->name('mutasi.exportExcel');


    });
});
