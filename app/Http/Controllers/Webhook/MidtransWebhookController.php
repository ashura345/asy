<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\RiwayatPembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Memproses notifikasi Midtrans.
 */
class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $orderId     = $payload['order_id']      ?? null;
        $statusCode  = $payload['status_code']   ?? null;
        $grossAmount = $payload['gross_amount']  ?? null;
        $signature   = $payload['signature_key'] ?? null;
        $paymentType = $payload['payment_type']  ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        // Validasi signature
        $serverKey = config('services.midtrans.server_key');
        $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!$signature || $signature !== $localSignature) {
            Log::warning('Midtrans signature mismatch', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $riwayat = RiwayatPembayaran::where('order_id', $orderId)->first();
        if (!$riwayat) {
            Log::warning('Midtrans order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Periksa jumlah gross
        if ((float) $riwayat->gross_amount > 0 && (float) $riwayat->gross_amount != (float) $grossAmount) {
            Log::warning('Gross amount mismatch', [
                'order_id' => $orderId,
                'expected' => $riwayat->gross_amount,
                'got'      => $grossAmount,
            ]);
        }

        DB::transaction(function () use ($riwayat, $paymentType, $transactionStatus, $fraudStatus, $payload) {
            $updates = [
                'payment_type'       => $paymentType,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
                'settlement_time'    => null,
            ];

            if (in_array($transactionStatus, ['settlement', 'capture'], true) && ($fraudStatus ?? 'accept') !== 'challenge') {
                if ($riwayat->status !== 'lunas') {
                    $updates['status']        = 'lunas';
                    $updates['tanggal_bayar'] = Carbon::now();
                    $updates['settlement_time'] = isset($payload['settlement_time'])
                        ? Carbon::parse($payload['settlement_time'])
                        : Carbon::now();
                }
            } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'], true)) {
                $updates['status'] = 'gagal';
            } else {
                $updates['status'] = 'pending';
            }

            $riwayat->update($updates);
        });

        return response()->json(['message' => 'Webhook processed']);
    }
}
