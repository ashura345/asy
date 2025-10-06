<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        return response()->json(Pembayaran::all());
    }

    public function riwayat()
    {
        return response()->json([
            'data' => Pembayaran::where('status', 'lunas')->get()
        ]);
    }
}
