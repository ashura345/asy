<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\KategoriPembayaranController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\LaporanController;

use App\Http\Controllers\Siswa\AuthController as SiswaAuthController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\SiswaPembayaranController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Siswa\SiswaRiwayatController;
use App\Http\Controllers\Siswa\PaymentController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\ChartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di file ini kita mendefinisikan URL‐URL aplikasi, termasuk rute untuk
| admin dan siswa, serta route Midtrans dan lainnya.
|
*/

// Halaman Awal
Route::get('/', function () {
    return view('welcome');
});

// ================== AUTH ROUTES ==================
// Login Admin
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Login & Register Siswa
Route::get('/siswa/login', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
Route::post('/siswa/login', [SiswaAuthController::class, 'login'])->name('siswa.login.submit');
Route::post('/siswa/logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

Route::get('/siswa/register', [SiswaAuthController::class, 'showRegisterForm'])->name('siswa.register');
Route::post('/siswa/register', [SiswaAuthController::class, 'register'])->name('siswa.register.submit');

// Laravel default auth (email verification, password reset, dsb.)
require __DIR__ . '/auth.php';
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ================== PROTECTED ROUTES ==================
Route::middleware(['auth', 'verified'])->group(function () {

    // ================== ADMIN ROUTES ==================
    Route::prefix('admin')->middleware('is_admin')->name('admin.')->group(function () {

        // Redirect base admin ke dashboard
        Route::get('/', fn () => redirect()->route('admin.dashboard'));

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
         Route::get('/dashboard/live', [DashboardController::class, 'liveChartData'])->name('dashboard.live');

        // Manajemen Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Kategori Pembayaran
        Route::get('/kategori', [KategoriPembayaranController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [KategoriPembayaranController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [KategoriPembayaranController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [KategoriPembayaranController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [KategoriPembayaranController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriPembayaranController::class, 'destroy'])->name('kategori.destroy');

        // Pembayaran (Admin)
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/', [PembayaranController::class, 'index'])->name('index');
            Route::get('/create', [PembayaranController::class, 'create'])->name('create');
            Route::post('/', [PembayaranController::class, 'store'])->name('store');
            Route::get('/{pembayaran}/edit', [PembayaranController::class, 'edit'])->name('edit');
            Route::put('/{pembayaran}', [PembayaranController::class, 'update'])->name('update');
            Route::delete('/{pembayaran}', [PembayaranController::class, 'destroy'])->name('destroy');
        });

        // Kasir (Admin)
        Route::prefix('kasir')->name('kasir.')->group(function () {
            Route::get('/', [KasirController::class, 'index'])->name('index');
             Route::get('/{pivotId}/bayar', [KasirController::class, 'bayarForm'])->name('bayarForm');
            Route::post('/{pivotId}/proses', [KasirController::class, 'prosesBayar'])->name('proses');
            Route::post('/payment-tunai/{pivotId}', [KasirController::class, 'prosesPaymentTunai'])->name('paymentTunai');        
        });

        // Laporan (Admin)
    Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [LaporanController::class, 'exportPDF'])->name('export.pdf');
});


     
    });

    // ================== SISWA ROUTES ==================
    Route::prefix('siswa')->middleware('is_siswa')->name('siswa.')->group(function () {

        // Redirect base siswa ke dashboard
        Route::get('/', fn () => redirect()->route('siswa.dashboard'));

        // Dashboard (Siswa)
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // Daftar Tagihan Pembayaran (Siswa)
        Route::get('/pembayaran', [SiswaPembayaranController::class, 'index'])
             ->name('pembayaran.index');

        // Detail Tagihan Pembayaran
        Route::get('/pembayaran/{id}', [SiswaPembayaranController::class, 'show'])
             ->name('pembayaran.show');

        // **Konfirmasi Pembayaran Tunai** (menyamai route name di Blade)
        Route::post('/pembayaran/{id}/konfirmasi-tunai', [SiswaPembayaranController::class, 'konfirmasiTunai'])
             ->name('pembayaran.konfirmasiTunai');

        // (Pilihan Anda: jika masih butuh route bayar-transfer / prosesMidtrans,
        //  bisa dibiarkan atau diganti sesuai kebutuhan. Contoh:)
        Route::post('/pembayaran/{id}/bayar-transfer', [SiswaPembayaranController::class, 'bayarTransfer'])
             ->name('pembayaran.bayarTransfer');

        Route::post('/pembayaran/{id}/proses', [SiswaPembayaranController::class, 'prosesPembayaran'])
             ->name('pembayaran.proses');

             Route::get('/pembayaran/{id}/generate-token', [SiswaPembayaranController::class, 'generateToken'])
         ->name('pembayaran.generateToken');

         Route::post('/midtrans/notification', [SiswaPembayaranController::class, 'notificationHandler'])->name('midtrans.callback');

         // Webhook Midtrans (set di Dashboard Midtrans → Payment Notification URL)
        Route::post('/midtrans/notify', [SiswaPembayaranController::class, 'notificationHandler'])
         ->name('midtrans.notify');


        // Jika sebelumnya ada route salah defined seperti:
        // Route::post('/siswa/pembayaran/tunai/{id}', ...)
        // silakan dihapus atau diganti karena sudah kita definisikan di atas.

        // Midtrans Callback (jika menggunakan PaymentController untuk webhook)
        Route::post('/bayar-midtrans', [MidtransController::class, 'pay'])->name('midtrans.pay');
        Route::post('/midtrans/callback', [PaymentController::class, 'handleNotification'])->name('midtrans.callback');
        Route::post('/midtrans/notification', [SiswaPembayaranController::class, 'notificationHandler'])->name('midtrans.callback');
        // Profil (Siswa)
        Route::resource('/profile', SiswaProfileController::class)->names('profile');

        // Riwayat Pembayaran (Siswa)
        Route::prefix('riwayat')->name('riwayat.')->group(function () {
    Route::get('/', [SiswaRiwayatController::class, 'index'])->name('index');
    Route::get('/cetak/{id}', [SiswaRiwayatController::class, 'cetak'])->name('cetak');
    Route::get('/cetak-pdf/{id}', [SiswaRiwayatController::class, 'cetakPDF'])->name('cetak.pdf');
});
        // Transfer Manual (opsional)
        Route::view('/pembayaran/transfer', 'pembayaran.transfer')->name('pembayaran.transfer');
    });
});

// ================== CHART ROUTES ==================
Route::get('/api/chart-data', [ChartController::class, 'monthly']);
Route::get('/grafik-pembayaran', function () {
    return view('chart');
});
