<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\KategoriPembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::all();
        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $siswa = Siswa::all();
        $kategoriPembayaran = KategoriPembayaran::all();
        return view('admin.pembayaran.create', compact('siswa', 'kategoriPembayaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'kategori_id' => 'required',
            'jumlah_tagihan' => 'required|numeric',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        Pembayaran::create($request->all());

        return redirect()->route('admin.pembayaran.index');
    }

    public function destroy($id)
    {
        Pembayaran::destroy($id);
        return redirect()->route('admin.pembayaran.index');
    }
}
