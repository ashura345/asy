<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pembayaran lunas & belum lunas (kalau ada kolom status)
        $totalLunas = Transaksi::where('status', 'lunas')->sum('total_bayar');
        $totalBelumLunas = Transaksi::where('status', 'belum_lunas')->sum('total_bayar');

        // Kalau belum ada kolom status di tabel transaksis, ganti jadi total keseluruhan:
        // $totalLunas = Transaksi::sum('total_bayar');
        // $totalBelumLunas = 0;

        // Statistik lainnya
        $totalSiswa = User::count();
        $totalTransaksi = Transaksi::count();

        // Jatuh tempo dummy (kamu bisa ganti sesuai logika real)
        $transaksiJatuhTempo = Transaksi::whereBetween('waktu', [now(), now()->addDays(3)])->count();

        // Data untuk grafik
        $chartData = Transaksi::select(
                DB::raw('MONTH(waktu) as month'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $months = $chartData->pluck('month');
        $totals = $chartData->pluck('total');

        return view('admin.dashboard', compact(
            'totalLunas',
            'totalBelumLunas',
            'totalSiswa',
            'totalTransaksi',
            'transaksiJatuhTempo',
            'months',
            'totals'
        ));
    }
}
