<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;

class RiwayatController extends Controller
{
    /**
     * Tampilkan halaman riwayat pembayaran siswa.
     */
    public function index()
    {
        $riwayat = Pembayaran::where('user_id', Auth::id())
                    ->where('status_pembayaran', 'Lunas')
                    ->latest()
                    ->get();

        return view('siswa.riwayat.index', compact('riwayat'));
    }

    /**
     * Tampilkan struk pembayaran dalam halaman biasa (tanpa PDF).
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function cetak($id)
    {
        $pembayaran = Pembayaran::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        // Menampilkan halaman struk biasa, bukan PDF
        return view('siswa.riwayat.struk', compact('pembayaran'));
    }
}
