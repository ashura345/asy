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
        // Statistik pembayaran lunas dan belum lunas
        $totalLunas = Pembayaran::where('status', 'lunas')->count();
        $totalBelumLunas = Pembayaran::where('status', 'belum lunas')->count();

        // Statistik kategori pembayaran
        $kategoriPembayaran = KategoriPembayaran::all();

        // Statistik siswa
        $totalSiswa = Siswa::count();

        // Statistik transaksi
        $totalTransaksi = Pembayaran::count();

        // Pengingat jatuh tempo dalam 3 hari
        $tanggalJatuhTempo = Carbon::now()->addDays(3);
        $transaksiJatuhTempo = Pembayaran::where('status', 'belum lunas')
                                          ->where('tanggal_jatuh_tempo', '<=', $tanggalJatuhTempo)
                                          ->count();

        // Statistik pembayaran berdasarkan bulan (untuk line chart)
        $payments = Pembayaran::selectRaw('MONTH(created_at) as month, SUM(jumlah) as total_payment')
                              ->groupBy('month')
                              ->whereYear('created_at', Carbon::now()->year)
                              ->get();

        // Siapkan data untuk chart
        $months = $payments->pluck('month');
        $totals = $payments->pluck('total_payment');

        // Mengirim semua data ke view
        return view('admin.dashboard', compact(
            'totalLunas', 
            'totalBelumLunas', 
            'kategoriPembayaran', 
            'totalSiswa', 
            'totalTransaksi', 
            'transaksiJatuhTempo', 
            'months', 
            'totals'
        ));
    }
}
