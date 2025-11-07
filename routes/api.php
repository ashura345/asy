<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use App\Http\Controllers\Api\SiswaAuthController;

// Webhook (tanpa auth)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

// === AUTH ===

Route::post('/login-siswa', [SiswaAuthController::class, 'login']);
Route::post('/logout-siswa', [SiswaAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

// === DATA UNTUK APP ===
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',        [AuthController::class, 'me'])->name('api.me');
    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('api.pembayarans');
    Route::get('/riwayat',     [PembayaranController::class, 'riwayat'])->name('api.riwayat');
});
