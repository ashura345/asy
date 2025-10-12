<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class SiswaPembayaranController extends Controller
{
    public function __construct()
    {
        // atur middleware di routes/web.php jika perlu
    }

    /**
     * Menampilkan daftar tagihan siswa
     */
    public function index()
    {
        $user = Auth::user();
        $data['tagihans'] = Pembayaran::where('kelas', $user->kelas)->get();
        return view('siswa.pembayaran.index', $data);
    }

    /**
     * Menampilkan detail tagihan siswa (tanpa generate token di sini)
     */
    public function show($id)
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::with(['kategori', 'siswa'])->findOrFail($id);

        $pivot = $pembayaran->siswa()->where('user_id', $user->id)->first()?->pivot;

        // Normalisasi status pivot (handle 'belum lunas', 'belum_lunas', dll)
        $statusRaw = $this->normalizeStatus($pivot?->status ?? 'belum-lunas');

        // Metode (kalau ada)
        $metodePivot = $pivot?->metode ?? null;

        $snapToken = null; // token di-generate lewat AJAX

        // kirim statusRaw & metodePivot ke view (supaya blade tidak perlu query ulang)
        return view('siswa.pembayaran.show', compact('pembayaran', 'statusRaw', 'metodePivot', 'snapToken'));
    }

    /**
     * Generate token Midtrans dinamis (dipanggil via AJAX)
     * Mengembalikan JSON: { "snapToken": "..." }
     */
    public function generateToken($id)
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::with(['siswa'])->findOrFail($id);

        $pivot = $pembayaran->siswa()->where('user_id', $user->id)->first()?->pivot;
        $statusRaw = $this->normalizeStatus($pivot?->status ?? 'belum-lunas');

        // Hanya boleh generate token jika status masih menunggu/ belum lunas
        if (!$this->isWaiting($statusRaw)) {
            return response()->json([
                'error' => 'Tagihan tidak dalam status menunggu pembayaran'
            ], 400);
        }

        // Load konfigurasi Midtrans
        Config::$serverKey    = config('midtrans.midtrans.server_key');
        Config::$isProduction = config('midtrans.midtrans.is_production');
        Config::$isSanitized  = config('midtrans.midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.midtrans.is_3ds');

        // Siapkan parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $pembayaran->id . '-USER-' . $user->id . '-' . time(),
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'customer_details'    => [
                'first_name' => $user->name,
                'email'      => $user->email ?? 'guest@example.com',
            ],
            'enabled_payments'    => ['bank_transfer', 'gopay', 'credit_card'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Token Error (generateToken): " . $e->getMessage());
            return response()->json([
                'error' => 'Gagal generate token: ' . $e->getMessage()
            ], 500);
        }

        return response()->json(['snapToken' => $snapToken]);
    }

    /**
     * Proses konfirmasi tunai (submit form dari detail tagihan)
     */
    public function konfirmasiTunai(Request $request, $id)
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::where('id', $id)
                                ->where('kelas', $user->kelas)
                                ->firstOrFail();

        $pembayaran->siswa()->syncWithoutDetaching([
            $user->id => [
                'status'             => 'menunggu-verifikasi',
                'tanggal_pembayaran' => now(),
                'metode'             => 'tunai',
            ]
        ]);

        return redirect()
            ->route('siswa.pembayaran.index')
            ->with('success', 'Pembayaran tunai berhasil dikonfirmasi, silakan tunggu verifikasi kasir.');
    }

    /**
     * Callback Midtrans (webhook) untuk update status transfer
     * Route: POST /midtrans/notification
     */
    public function notificationHandler(Request $request)
{
    // Pastikan config Midtrans
    Config::$serverKey    = config('midtrans.midtrans.server_key');     // konsisten
    Config::$isProduction = config('midtrans.midtrans.is_production');
    Config::$isSanitized  = config('midtrans.midtrans.is_sanitized');
    Config::$is3ds        = config('midtrans.midtrans.is_3ds');

    // Log payload mentah (debug)
    Log::info('Notification Received', ['json' => $request->all()]);

    try {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $paymentType       = $notification->payment_type;
        $orderId           = $notification->order_id;
        $signatureKey      = $notification->signature_key;
        $grossAmount       = $notification->gross_amount;   // string "20000.00"
        $statusCode        = $notification->status_code ?? null;
        $fraudStatus       = $notification->fraud_status ?? null;

        // Signature resmi + fallback lama
        $serverKey  = config('midtrans.midtrans.server_key');
        $sigOfficial = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        $sigLegacy   = hash('sha512', $orderId.$transactionStatus.$grossAmount.$serverKey);

        if (!hash_equals($signatureKey, $sigOfficial) && !hash_equals($signatureKey, $sigLegacy)) {
            Log::warning("[Midtrans] Invalid signature: {$orderId}");
            return response()->json(['ok' => true]); // balas 200 supaya Midtrans tidak retry terus
        }

        if (!preg_match('/^ORDER-(\d+)-USER-(\d+)-\d+$/', (string) $orderId, $m)) {
            Log::warning("[Midtrans] Invalid order_id format: {$orderId}");
            return response()->json(['ok' => true]);
        }
        $idPembayaran = (int) $m[1];
        $userId       = (int) $m[2];

        $pembayaran = Pembayaran::find($idPembayaran);
        if (!$pembayaran) {
            Log::warning("[Midtrans] Pembayaran {$idPembayaran} tidak ditemukan");
            return response()->json(['ok' => true]);
        }
        $pivot = $pembayaran->siswa()->where('user_id', $userId)->first()?->pivot;
        if (!$pivot) {
            Log::warning("[Midtrans] Pivot {$idPembayaran} user {$userId} tidak ditemukan");
            return response()->json(['ok' => true]);
        }

        // CC capture + accept -> settlement
        if ($paymentType === 'credit_card' && $transactionStatus === 'capture' && ($fraudStatus === 'accept')) {
            $transactionStatus = 'settlement';
        }

        // Idempotent
        if (($pivot->status ?? null) === 'lunas' && $transactionStatus === 'settlement') {
            return response()->json(['ok' => true]);
        }

        if ($transactionStatus === 'settlement') {
            $settledAt = $notification->settlement_time ?? now();

            $pembayaran->siswa()->syncWithoutDetaching([
                $userId => [
                    'status'             => 'lunas',
                    'metode'             => 'transfer',
                    'tanggal_pembayaran' => $settledAt,
                    'payment_type'       => $paymentType,
                    'transaction_status' => $transactionStatus,
                    'jumlah_bayar'       => (int) round((float)$grossAmount), // simpan nominal
                    'order_id'           => $orderId,
                ]
            ]);

            $pembayaran->update(['status' => 'Lunas']);
            Log::info("[Midtrans] SETTLED pembayaran {$idPembayaran} user {$userId}");
        } elseif (in_array($transactionStatus, ['deny','cancel','expire'])) {
            $pembayaran->siswa()->syncWithoutDetaching([
                $userId => [
                    'status'             => 'dibatalkan',
                    'payment_type'       => $paymentType,
                    'transaction_status' => $transactionStatus,
                ]
            ]);
            Log::info("[Midtrans] {$transactionStatus} pembayaran {$idPembayaran} user {$userId}");
        } else {
            $pembayaran->siswa()->syncWithoutDetaching([
                $userId => [
                    'status'             => 'menunggu-pembayaran',
                    'payment_type'       => $paymentType,
                    'transaction_status' => $transactionStatus,
                ]
            ]);
            Log::info("[Midtrans] OTHER {$transactionStatus} {$orderId}");
        }

        return response()->json(['ok' => true]); // selalu 200
    } catch (\Throwable $e) {
        Log::error('[Midtrans] EXCEPTION: '.$e->getMessage().' @ '.$e->getFile().':'.$e->getLine());
        return response()->json(['ok' => true]); // 200 supaya tidak 502 di ngrok
    }
}


    /* ===========================
     * Helpers
     * =========================== */
    private function normalizeStatus(string $status): string
    {
        $raw = strtolower(trim($status));
        $raw = str_replace(['_', ' '], '-', $raw);

        $waiting = ['belum-lunas','menunggu-pembayaran','pending','unpaid'];
        if (in_array($raw, $waiting)) return 'belum-lunas';
        if ($raw === 'menunggu-verifikasi') return 'menunggu-verifikasi';
        if ($raw === 'lunas') return 'lunas';
        if (in_array($raw, ['dibatalkan','batal','cancelled'])) return 'dibatalkan';
        return $raw;
    }

    private function isWaiting(string $normalized): bool
    {
        return in_array($normalized, ['belum-lunas','dibatalkan']); // boleh bayar ulang kalau dibatalkan
    }
}
