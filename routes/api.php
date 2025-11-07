<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use App\Http\Controllers\Api\SiswaAuthController;

// Webhook (tanpa auth)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

// === AUTH ===

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// === DATA UNTUK APP ===
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('api.pembayarans');
    Route::get('/riwayat',     [PembayaranController::class, 'riwayat'])->name('api.riwayat');
});

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Api berhasil terhubung ke flutter!'
    ]);
});

