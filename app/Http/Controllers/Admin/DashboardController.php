<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\KategoriPembayaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        // NOMINAL LUNAS (MTD)
        // Tetap gunakan tabel Pembayaran jika memang di sinilah nominal final disimpan.
        $nominalLunas = (float) Pembayaran::where('status', 'lunas')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');

        // OUTSTANDING — pastikan nilai status ini sesuai dengan yang ada di DB kamu
        // Jika di DB pakainya 'pending' atau 'belum-lunas', sesuaikan di sini.
        $nominalOutstanding = (float) Pembayaran::where('status', 'belum lunas')
            ->sum('jumlah');

        // TOTAL TRANSAKSI (MTD) — semua status
        $totalTransaksi = Pembayaran::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // ====== DATA SISWA (untuk kartu + filter dropdown) ======
        $kelasCounts = Siswa::selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas'); // ['1'=>5, '2'=>6, ...]

        $totalSiswa   = $kelasCounts->sum();
        $kelasOptions = $kelasCounts->keys()->values(); // ['1','2','3',...]

        // JATUH TEMPO
        $transaksiJatuhTempo = Pembayaran::where('status', 'belum lunas')
            ->whereDate('tanggal_tempo', '<=', now())
            ->count();

        // ====== GRAFIK 12 BULAN (total by bulan) ======
        $year = now()->year;

        $grp = Pembayaran::selectRaw('MONTH(created_at) as m, SUM(jumlah) as total')
            ->whereYear('created_at', $year)
            ->groupBy('m')
            ->pluck('total', 'm'); // [1=>..., 2=>..., ...]

        $months = [];
        $totals = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create(null, $m, 1)->translatedFormat('F'); // Januari, dst
            $totals[] = (float) ($grp[$m] ?? 0);
        }

        $kategoriPembayaran = KategoriPembayaran::all();

        return view('admin.dashboard', compact(
            'nominalLunas',
            'nominalOutstanding',
            'totalTransaksi',
            'totalSiswa',
            'kelasCounts',
            'kelasOptions',
            'months',
            'totals',
            'year',
            'transaksiJatuhTempo',
            'kategoriPembayaran'
        ));
    }
}
