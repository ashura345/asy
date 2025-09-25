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
        // Pengaturan middleware bisa dikontrol di routes/web.php sesuai dengan kebutuhan
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
     * Menampilkan detail tagihan siswa, tanpa generate token di sini
     */
    public function show($id)
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::with(['kategori', 'siswa'])->findOrFail($id);

        // Ambil status pivot (jika pernah di‐sync)
        $pivotData = $pembayaran->siswa()->where('user_id', $user->id)->first()?->pivot;
        $status = $pivotData?->status ?? 'belum-lunas';

        $snapToken = null; // token di‐generate via AJAX

        return view('siswa.pembayaran.show', compact('pembayaran', 'status', 'snapToken'));
    }

    /**
     * GENERATE TOKEN MIDTRANS DINAMIS
     * Dipanggil lewat AJAX saat siswa menekan tombol “Transfer” di view.
     * Mengembalikan JSON: { "snapToken": "..." }
     */
    public function generateToken($id)
    {
        $user = Auth::user();
        $pembayaran = Pembayaran::with(['siswa'])->findOrFail($id);

        $pivotData = $pembayaran->siswa()->where('user_id', $user->id)->first()?->pivot;
        $status = $pivotData?->status ?? 'belum-lunas';

        // Hanya generate token jika status = 'belum-lunas'
        if ($status !== 'belum-lunas') {
            return response()->json([
                'error' => 'Pembayaran tidak dalam status belum-lunas'
            ], 400);
        }

        // Load konfigurasi Midtrans (nested di config/midtrans.php)
        Config::$serverKey    = config('midtrans.midtrans.server_key');
        Config::$isProduction = config('midtrans.midtrans.is_production');
        Config::$isSanitized  = config('midtrans.midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.midtrans.is_3ds');

        // Siapkan parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $pembayaran->id . '-USER-' . $user->id . '-' . time(),
                'gross_amount' => $pembayaran->jumlah,
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

        // Kembalikan JSON dengan token
        return response()->json([
            'snapToken' => $snapToken
        ]);
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
     * Callback Midtrans (webhook) untuk men‐update status transfer menjadi lunas/dibatalkan.
     * Route: POST /midtrans/notification
     */
    public function notificationHandler(Request $request)
    {
        // Load konfigurasi Midtrans agar Notification dapat memverifikasi
        Config::$serverKey    = config('midtrans.midtrans.server_key');
        Config::$isProduction = config('midtrans.midtrans.is_production');
        Config::$isSanitized  = config('midtrans.midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.midtrans.is_3ds');

        $notification = new Notification(); // membaca JSON dari Midtrans otomatis

        $transactionStatus = $notification->transaction_status; // mis: 'settlement'
        $paymentType       = $notification->payment_type;       // mis: 'bank_transfer'
        $orderId           = $notification->order_id;           // format: ORDER-{pembayaran_id}-USER-{user_id}-{timestamp}
        $signatureKey      = $notification->signature_key;      // signature untuk verifikasi
        $grossAmount       = $notification->gross_amount;       // jumlah transaksi
        $fraudStatus       = $notification->fraud_status ?? null; // mis: 'accept'

        // Verifikasi signature
        $serverKey = config('midtrans.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId.$transactionStatus.$grossAmount.$serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning("[Midtrans] Invalid signature: {$orderId}");
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Extract pembayaran_id dan user_id dari order_id
        $parts = explode('-', $orderId);
        $idPembayaran = $parts[1] ?? null;
        $userId = $parts[3] ?? null;

        if (!$idPembayaran || !$userId) {
            Log::warning("[Midtrans] Order ID format tidak valid: {$orderId}");
            return response()->json(['message' => 'Invalid order_id format'], 400);
        }

        // Cari model Pembayaran
        $pembayaran = Pembayaran::find($idPembayaran);
        if (!$pembayaran) {
            Log::warning("[Midtrans] Pembayaran ID {$idPembayaran} tidak ditemukan");
            return response()->json(['message' => 'Pembayaran not found'], 404);
        }

        // Cari pivot baris untuk user ini
        $pivotData = $pembayaran->siswa()->where('user_id', $userId)->first()?->pivot;
        if (!$pivotData) {
            Log::warning("[Midtrans] Pivot untuk pembayaran {$idPembayaran} dan user {$userId} tidak ditemukan");
            return response()->json(['message' => 'Pivot not found'], 404);
        }

        // Tangani masing‐masing status transaksi
        if ($transactionStatus === 'settlement') {
            // Pembayaran berhasil → set status = 'lunas'
            $pembayaran->siswa()->updateExistingPivot($userId, [
                'status'             => 'lunas',
                'metode'             => 'transfer',
                'tanggal_pembayaran' => now(),
                'payment_type'       => $paymentType,
                'transaction_status' => $transactionStatus,
            ]);
            Log::info("[Midtrans] Pembayaran ID {$idPembayaran} untuk user {$userId} telah lunas");

            // Redirect ke halaman index setelah pembayaran berhasil
            return redirect()->route('siswa.pembayaran.index')->with('success', 'Pembayaran berhasil dan status telah diperbarui menjadi lunas.');
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            // Pembayaran gagal atau dibatalkan → set status = 'dibatalkan'
            $pembayaran->siswa()->updateExistingPivot($userId, [
                'status'             => 'dibatalkan',
                'payment_type'       => $paymentType,
                'transaction_status' => $transactionStatus,
            ]);
            Log::info("[Midtrans] Pembayaran ID {$idPembayaran} untuk user {$userId} status: {$transactionStatus}");
        } else {
            Log::info("[Midtrans] Status transaksi lain untuk {$orderId}: {$transactionStatus}");
        }

        return response()->json(['message' => 'Notifikasi Midtrans diproses']);
    }
}
