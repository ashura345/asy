<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\KategoriPembayaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        // Menampilkan semua pembayaran kasir
        $pembayaran = Pembayaran::where('status', 'lunas')->get();
        return view('admin.kasir.index', compact('pembayaran'));
    }

    public function create()
    {
        // Menampilkan form pembayaran manual
        $siswa = Siswa::all();  // Ambil data siswa
        $kategoriPembayaran = KategoriPembayaran::all();  // Ambil data kategori pembayaran
        return view('admin.kasir.create', compact('siswa', 'kategoriPembayaran'));
    }

    public function store(Request $request)
    {
        // Validasi dan simpan pembayaran manual
        $request->validate([
            'siswa_id' => 'required',
            'kategori_pembayaran_id' => 'required',
            'nominal' => 'required|numeric',
            'status' => 'required',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);

        Pembayaran::create([
            'siswa_id' => $request->siswa_id,
            'kategori_pembayaran_id' => $request->kategori_pembayaran_id,
            'nominal' => $request->nominal,
            'status' => $request->status,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        ]);

        return redirect()->route('admin.kasir.index');
    }

    public function destroy($id)
    {
        // Menghapus pembayaran kasir
        Pembayaran::destroy($id);
        return redirect()->route('admin.kasir.index');
    }
}
