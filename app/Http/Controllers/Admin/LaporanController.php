<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Exports\PembayaranExport; // Pastikan file ini ada
use App\Mail\TunggakanPembayaranMail; // Pastikan file ini ada
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start  = $request->start_date;
        $end    = $request->end_date;
        $mode   = $request->get('mode', 'lunas'); // Default 'lunas'

        // Paksa mode tunggakan jika akses route khusus
        if ($request->routeIs('admin.laporan.tunggakan')) {
            $mode = 'tunggakan';
        }

        $kelasFilter       = $request->get('kelas');
        $pembayaranFilter  = $request->get('pembayaran_id');

        // --- Data Dropdown Filter ---
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

        // --- Base Query Join ---
        $baseQuery = DB::table('pembayaran_user')
            ->join('users', 'pembayaran_user.user_id', '=', 'users.id')
            ->join('pembayarans', 'pembayaran_user.pembayaran_id', '=', 'pembayarans.id');

        // ================== MODE LUNAS ==================
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
        // ================== MODE TUNGGAKAN ==================
        else {
            $query = clone $baseQuery;
            $query->where('pembayaran_user.status', '!=', 'lunas');

            if ($kelasFilter) {
                $query->where('users.kelas', $kelasFilter);
            }

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

        // Tentukan view
        $viewName = $mode === 'tunggakan'
            ? 'admin.laporan.tunggakan' // Sesuaikan jika nama file view tunggakan beda
            : 'admin.laporan.index';

        return view($viewName, compact(
            'laporans', 'start', 'end', 'mode',
            'daftarKelas', 'daftarPembayaran', 'kelasFilter', 'pembayaranFilter'
        ));
    }

    // ===== Export Excel =====
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PembayaranExport($request->start_date, $request->end_date),
            'laporan_pembayaran_' . date('d-m-Y') . '.xlsx'
        );
    }

    // ===== Export PDF (FIXED) =====
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

        // PERBAIKAN: Menggunakan 'as' bukan 'sebagai'
        $laporans = $query->select([
                'users.name as nama_siswa',
                'users.kelas',
                'pembayarans.nama as nama_pembayaran', // FIXED
                'pembayarans.jumlah',
                'pembayaran_user.tanggal_pembayaran as tanggal_bayar',
                'pembayaran_user.metode',
            ])
            ->orderByDesc('pembayaran_user.tanggal_pembayaran')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporans', 'start', 'end'))
            ->setPaper('A4', 'landscape'); // Landscape agar tabel muat

        return $pdf->download('laporan_pembayaran_' . date('d-m-Y') . '.pdf');
    }

    // ===== Kirim Email =====
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

        // Pastikan Mail TunggakanPembayaranMail sudah dibuat
        Mail::to($item->email)->send(new TunggakanPembayaranMail($item));

        return back()->with(
            'success',
            'Notifikasi tunggakan berhasil dikirim ke ' . $item->nama_siswa
        );
    }
}