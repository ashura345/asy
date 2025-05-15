<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Create a new payment and return Snap token.
     */
    public function createPayment(Request $request)
    {
        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
        ]);

        // Persiapan data transaksi
        $transactionDetails = [
            'order_id' => Str::uuid()->toString(), // Lebih baik daripada uniqid()
            'gross_amount' => (int) $request->amount,
        ];

        $itemDetails = [
            [
                'id' => uniqid(),
                'price' => (int) $request->amount,
                'quantity' => 1,
                'name' => 'Payment for ' . $request->description,
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
        ];

        try {
            $snapToken = Snap::getSnapToken($paymentTransaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment initiation failed: ' . $e->getMessage()], 500);
        }
    }
}
