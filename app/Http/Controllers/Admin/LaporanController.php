<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Exports\PembayaranExport;
use App\Mail\TunggakanPembayaranMail;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start  = $request->start_date;
        $end    = $request->end_date;
        $mode   = $request->get('mode', 'lunas'); // 'lunas' atau 'tunggakan'
        $kelasFilter = $request->get('kelas');
        $pembayaranFilter = $request->get('pembayaran_id');

        // --- dropdown data untuk filter kelas & pembayaran (dipakai di mode tunggakan) ---
        $daftarKelas = DB::table('users')
            ->whereNotNull('kelas')
            ->where('role', 'siswa')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $daftarPembayaran = DB::table('pembayarans')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        // Base query join
        $baseQuery = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id');

        // ================== MODE LUNAS (SUDAH BAYAR) ==================
        if ($mode === 'lunas') {
            $query = clone $baseQuery;
            $query->where('pembayaran_user.status', 'lunas');

            if ($start && $end) {
                $query->whereBetween('pembayaran_user.tanggal_pembayaran', [
                    $start . ' 00:00:00',
                    $end . ' 23:59:59',
                ]);
            }

            $laporans = $query->select([
                    'pembayaran_user.id as pembayaran_user_id',
                    'users.name as nama_siswa',
                    'users.kelas',
                    'users.email',
                    'pembayarans.nama as nama_pembayaran',
                    'pembayarans.jumlah',
                    'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                    'pembayaran_user.metode',
                    'pembayaran_user.status',
                ])
                ->orderByDesc('pembayaran_user.tanggal_pembayaran')
                ->get();
        }

        // ================== MODE TUNGGAKAN (BELUM LUNAS) ==================
        else {
            $query = clone $baseQuery;
            $query->where('pembayaran_user.status', '!=', 'lunas');

            // filter per kelas
            if ($kelasFilter) {
                $query->where('users.kelas', $kelasFilter);
            }

            // filter per jenis pembayaran
            if ($pembayaranFilter) {
                $query->where('pembayaran_user.pembayaran_id', $pembayaranFilter);
            }

            $laporans = $query->select([
                    'pembayaran_user.id as pembayaran_user_id',
                    'users.name as nama_siswa',
                    'users.kelas',
                    'users.email',
                    'pembayarans.nama as nama_pembayaran',
                    'pembayarans.jumlah',
                    'pembayaran_user.status',
                ])
                ->orderBy('users.kelas')
                ->orderBy('users.name')
                ->get();
        }

        return view('admin.laporan.index', compact(
            'laporans',
            'start',
            'end',
            'mode',
            'daftarKelas',
            'daftarPembayaran',
            'kelasFilter',
            'pembayaranFilter'
        ));
    }

    // ===== Export Excel (mode LUNAS) =====
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PembayaranExport($request->start_date, $request->end_date),
            'laporan_pembayaran.xlsx'
        );
    }

    // ===== Export PDF (mode LUNAS) =====
    public function exportPDF(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        $query = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.status', 'lunas');

        if ($start && $end) {
            $query->whereBetween('pembayaran_user.tanggal_pembayaran', [
                $start . ' 00:00:00',
                $end . ' 23:59:59',
            ]);
        }

        $laporans = $query->select([
                'users.name as nama_siswa',
                'users.kelas',
                'pembayarans.nama as nama_pembayaran',
                'pembayarans.jumlah',
                'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                'pembayaran_user.metode',
            ])
            ->orderByDesc('pembayaran_user.tanggal_pembayaran')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporans', 'start', 'end'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_pembayaran.pdf');
    }

    // ===== Kirim Email Notifikasi Tunggakan =====
    public function kirimEmailTunggakan($id)
    {
        $item = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id')
            ->where('pembayaran_user.id', $id)
            ->select([
                'pembayaran_user.id as pembayaran_user_id',
                'users.name as nama_siswa',
                'users.email',
                'users.kelas',
                'pembayarans.nama as nama_pembayaran',
                'pembayarans.jumlah',
                'pembayaran_user.status',
            ])
            ->first();

        if (!$item) {
            return back()->with('error', 'Data tunggakan tidak ditemukan.');
        }

        Mail::to($item->email)->send(new TunggakanPembayaranMail($item));

        return back()->with(
            'success',
            'Notifikasi tunggakan berhasil dikirim ke ' . $item->nama_siswa
        );
    }
}
