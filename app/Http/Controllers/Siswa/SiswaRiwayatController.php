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
     * Tampilkan riwayat pembayaran lunas siswa yang login.
     */
    public function index()
    {
        $user = Auth::user();

        $riwayatBayar = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.user_id', $user->id)
            ->where('pembayaran_user.status', 'lunas')
            ->orderByDesc('pembayaran_user.tanggal_pembayaran')
            ->select([
                'pembayaran_user.id',
                'pembayaran_user.pembayaran_id',
                'pembayarans.nama as nama_pembayaran',
                'pembayaran_user.jumlah as jumlah_bayar', // pakai kolom asli 'jumlah' tapi alias jadi 'jumlah_bayar'
                'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                'pembayaran_user.metode',
            ])
            ->get();

        return view('siswa.riwayat.index', compact('riwayatBayar'));
    }

    /**
     * Tampilkan halaman print preview struk pembayaran.
     */
    public function cetak($id)
    {
        $user = Auth::user();

        $data = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->where('pembayaran_user.user_id', $user->id)
            ->where('pembayaran_user.id', $id)
            ->where('pembayaran_user.status', 'lunas')
            ->select([
                'users.name as nama_siswa',
                'users.kelas',
                'pembayarans.nama as nama_pembayaran',
                'pembayaran_user.jumlah as jumlah_bayar',  // pakai 'jumlah'
                'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                'pembayaran_user.metode',
            ])
            ->first();

        if (! $data) {
            return redirect()->route('riwayat.index')
                             ->with('error', 'Struk tidak ditemukan.');
        }

        return view('siswa.riwayat.struk', compact('data'));
    }

    /**
     * Download file PDF struk pembayaran.
     */
    public function cetakPDF($id)
    {
        $user = Auth::user();

        $data = DB::table('pembayaran_user')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->where('pembayaran_user.user_id', $user->id)
            ->where('pembayaran_user.id', $id)
            ->where('pembayaran_user.status', 'lunas')
            ->select([
                'users.name as nama_siswa',
                'users.kelas',
                'pembayarans.nama as nama_pembayaran',
                'pembayaran_user.jumlah as jumlah_bayar',  // pakai 'jumlah'
                'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                'pembayaran_user.metode',
            ])
            ->first();

        if (! $data) {
            return redirect()->route('riwayat.index')
                             ->with('error', 'Struk tidak ditemukan.');
        }

        $pdf = Pdf::loadView('siswa.riwayat.struk_pdf', compact('data'))
                  ->setPaper('A4', 'portrait');

        $namaFile = 'struk_'
                  . str_replace(' ', '_', strtolower($data->nama_pembayaran))
                  . '_'
                  . date('Ymd_His', strtotime($data->tanggal_bayar))
                  . '.pdf';

        return $pdf->download($namaFile);
    }
}
