<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaRiwayatController extends Controller
{
    /**
     * Menampilkan daftar riwayat pembayaran lunas siswa.
     * Route: GET /riwayat
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $riwayat = DB::table('riwayat_pembayarans as rp')
            ->join('pembayarans as p', 'rp.pembayaran_id', '=', 'p.id')
            ->where('rp.user_id', $user->id)
            ->where('rp.status', 'lunas')
            ->orderByDesc('rp.tanggal_bayar')
            ->select([
                'rp.id',
                'rp.pembayaran_id',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                'rp.jumlah_bayar',
                'rp.tanggal_bayar',
                'rp.metode',
                'rp.status',
            ])
            ->get();

        return view('siswa.riwayat.index', compact('riwayat'));
    }

    /**
     * Menampilkan preview struk pembayaran.
     * Route: GET /riwayat/cetak/{id}
     */
    public function cetak($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = DB::table('riwayat_pembayarans as rp')
            ->join('pembayarans as p', 'rp.pembayaran_id', '=', 'p.id')
            ->join('users as u', 'rp.user_id', '=', 'u.id')
            ->where('rp.user_id', $user->id)
            ->where('rp.id', $id)
            ->where('rp.status', 'lunas')
            ->select([
                'u.name as nama_siswa',
                'u.kelas',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                'rp.jumlah_bayar',
                'rp.tanggal_bayar',
                'rp.metode',
                'rp.status',
            ])
            ->first();

        if (!$data) {
            // route() disesuaikan dengan prefix yang kamu pakai di web.php
            return redirect()->route('riwayat.index')->with('error', 'Struk tidak ditemukan.');
        }

        return view('siswa.riwayat.struk', compact('data'));
    }

    /**
     * Mengunduh struk sebagai PDF.
     * Route: GET /riwayat/cetak-pdf/{id}
     */
    public function cetakPDF($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $data = DB::table('riwayat_pembayarans as rp')
            ->join('pembayarans as p', 'rp.pembayaran_id', '=', 'p.id')
            ->join('users as u', 'rp.user_id', '=', 'u.id')
            ->where('rp.user_id', $user->id)
            ->where('rp.id', $id)
            ->where('rp.status', 'lunas')
            ->select([
                'u.name as nama_siswa',
                'u.kelas',
                'p.nama as nama_pembayaran',
                DB::raw('COALESCE(p.total, p.jumlah) as total_tagihan'),
                'rp.jumlah_bayar',
                'rp.tanggal_bayar',
                'rp.metode',
                'rp.status',
            ])
            ->first();

        if (!$data) {
            return redirect()->route('riwayat.index')->with('error', 'Struk tidak ditemukan.');
        }

        $pdf = Pdf::loadView('siswa.riwayat.struk_pdf', compact('data'))
                  ->setPaper('A4', 'portrait');

        $namaPembayaranSafe = preg_replace('/[^a-z0-9_\-]/i', '_', strtolower($data->nama_pembayaran));
        $namaFile = 'struk_' . $namaPembayaranSafe . '_' . date('Ymd_His', strtotime($data->tanggal_bayar)) . '.pdf';

        return $pdf->download($namaFile);
    }
}
