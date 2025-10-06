<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the start and end of the current month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        // Get the total nominal of payments that are 'lunas' (paid) in the current month
        $nominalLunas = (float) Pembayaran::where('status', 'lunas')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('jumlah');

        // Get the total outstanding (unpaid) amount
        $nominalOutstanding = (float) Pembayaran::where('status', 'belum lunas')
            ->sum('jumlah');

        // Get the total number of transactions in the current month (MTD)
        $totalTransaksi = Pembayaran::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Get the number of students per class for the filter dropdown
        $kelasCounts = Siswa::selectRaw('kelas, COUNT(*) as total')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->pluck('total', 'kelas');

        $totalSiswa   = $kelasCounts->sum();
        $kelasOptions = $kelasCounts->keys()->values();

        // Get the number of overdue payments (due payments)
        $transaksiJatuhTempo = Pembayaran::where('status', 'belum lunas')
            ->whereDate('tanggal_tempo', '<=', now())
            ->count();

        // Get monthly total payments for the last 12 months
        $year = now()->year;

        $grp = Pembayaran::selectRaw('MONTH(created_at) as m, SUM(jumlah) as total')
            ->whereYear('created_at', $year)
            ->groupBy('m')
            ->pluck('total', 'm'); // [1=>..., 2=>..., ...]

        $months = [];
        $totals = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = Carbon::create(null, $m, 1)->translatedFormat('F'); // January, February, ...
            $totals[] = (float) ($grp[$m] ?? 0);
        }

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
            'transaksiJatuhTempo'
        ));
    }
}
