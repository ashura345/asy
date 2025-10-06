<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Webhook\MidtransWebhookController;

// Webhook (tanpa auth)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

// === AUTH ===
Route::post('/login',  [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');

// === DATA UNTUK APP ===
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',        [AuthController::class, 'me'])->name('api.me');
    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('api.pembayarans');
    Route::get('/riwayat',     [PembayaranController::class, 'riwayat'])->name('api.riwayat');
});
