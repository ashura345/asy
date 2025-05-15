<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('siswa.pembayaran.index');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'metode' => 'required|in:tunai,transfer',
        ]);

        $kategori = $request->kategori;
        $metode = $request->metode;

        if ($metode === 'tunai') {
            return view('siswa.pembayaran.tunai', compact('kategori'));
        } elseif ($metode === 'transfer') {
            return view('siswa.pembayaran.transfer', compact('kategori'));
        }

        return redirect()->route('pembayaran.index');
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'amount' => 'required|numeric|min:1000',
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Detail transaksi
        $transactionDetails = [
            'order_id' => uniqid('ORDER-'),
            'gross_amount' => $request->amount,
        ];

        $itemDetails = [
            [
                'id' => uniqid('ITEM-'),
                'price' => $request->amount,
                'quantity' => 1,
                'name' => 'Pembayaran ' . $request->kategori,
            ]
        ];

        $customerDetails = [
            'first_name' => $request->user()->name ?? 'Guest',
            'email' => $request->user()->email ?? 'guest@example.com',
        ];

        $paymentTransaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'enabled_payments' => ['bank_transfer'], // Khusus transfer bank
        ];

        try {
            $snapToken = Snap::getSnapToken($paymentTransaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
