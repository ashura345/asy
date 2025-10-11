<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use App\Http\Controllers\Api\SiswaAuthController;
use GuzzleHttp\Psr7\Response;
use Illuminate\Cache\Repository;
use illuminate\Http\Request;

// Webhook (tanpa auth)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

// === AUTH ===
Route::post('/login', [AuthController::class, 'login']);

// === AUTH (dengan Sanctum token) ===
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
// === DATA UNTUK APP ===
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',        [AuthController::class, 'me'])->name('api.me');
    Route::get('/pembayarans', [PembayaranController::class, 'index'])->name('api.pembayarans');
    Route::get('/riwayat',     [PembayaranController::class, 'riwayat'])->name('api.riwayat');
});

Route::get('/test', function (){
    return Response()->json([
        'status' => 'success',
        'message' => 'Api berhasil terhubung ke flutter!'
    ]);
});

Route::post('/cekpost', function (Request $request) {
    return response()->json([
        'message' => 'POST diterima',
        'body' => $request->all()
    ]);
});