<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KategoriPembayaranController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\LaporanController;

use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\KategoriPembayaranController as SiswaKategoriPembayaranController;
use App\Http\Controllers\Siswa\PembayaranController as SiswaPembayaranController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Siswa\RiwayatController as SiswaRiwayatController;
use App\Http\Controllers\Siswa\PaymentController;
use App\Http\Controllers\MidtransController;

// Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes (bisa berisi auth umum seperti registrasi, login siswa, dll)
require __DIR__.'/auth.php';

// Login Admin
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Grup untuk user yang sudah login dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    // ================== ADMIN ROUTES ==================
    Route::prefix('admin')->middleware('role:admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Alias untuk redirect default jika diperlukan
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Siswa
        Route::resource('/siswa', SiswaController::class)->names('admin.siswa');

        // Kategori Pembayaran
        Route::resource('/kategori', KategoriPembayaranController::class)->names('admin.kategori');

        // Pembayaran
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran.index');
        Route::post('/pembayaran/pilih', [PembayaranController::class, 'proses'])->name('admin.pembayaran.proses');
        Route::post('/pembayaran/create', [PembayaranController::class, 'createPayment'])->name('admin.pembayaran.create');
        Route::get('/pembayaran/edit/{id}', [PembayaranController::class, 'edit'])->name('admin.pembayaran.edit');
        Route::post('/pembayaran/update/{id}', [PembayaranController::class, 'update'])->name('admin.pembayaran.update');
        Route::delete('/pembayaran/destroy/{id}', [PembayaranController::class, 'destroy'])->name('admin.pembayaran.destroy');

        // Kasir
        Route::resource('/kasir', KasirController::class)->names('admin.kasir');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPDF'])->name('admin.laporan.export.pdf');
        Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('admin.laporan.export.excel');
    });

    // ================== SISWA ROUTES ==================
    Route::prefix('siswa')->middleware('role:siswa')->group(function () {

        // Dashboard
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');

        // Kategori Pembayaran
        Route::resource('/kategori', SiswaKategoriPembayaranController::class)->names('siswa.kategori');

        // Pembayaran
        Route::get('/pembayaran', [SiswaPembayaranController::class, 'index'])->name('siswa.pembayaran.index');
        Route::post('/pembayaran/pilih', [SiswaPembayaranController::class, 'proses'])->name('siswa.pembayaran.proses');
        Route::post('/pembayaran/create', [SiswaPembayaranController::class, 'createPayment'])->name('siswa.pembayaran.create');

        // Midtrans
        Route::post('/bayar-midtrans', [MidtransController::class, 'pay'])->name('siswa.midtrans.pay');
        Route::post('/midtrans/callback', [PaymentController::class, 'handleNotification'])->name('siswa.midtrans.callback');

        // Profil
        Route::resource('/profile', SiswaProfileController::class)->names('siswa.profile');

        // Riwayat
        Route::get('/riwayat', [SiswaRiwayatController::class, 'index'])->name('siswa.riwayat.index');
        Route::get('/riwayat/cetak/{id}', [SiswaRiwayatController::class, 'cetak'])->name('siswa.riwayat.cetak');

        // Transfer Manual (Opsional)
        Route::get('/pembayaran/transfer', function () {
            return view('pembayaran.transfer');
        })->name('siswa.pembayaran.transfer');
    });
});
