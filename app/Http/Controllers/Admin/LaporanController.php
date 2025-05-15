<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf; // Perbaiki alias import PDF
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan dalam bentuk PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPDF()
    {
        // Ambil data untuk laporan
        $data = [
            'title' => 'Laporan Pembayaran',
            'content' => 'Ini adalah laporan pembayaran yang bisa diunduh.',
            'pembayarans' => Pembayaran::all()
        ];

        // Load view untuk PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', $data);

        // Kembalikan sebagai download
        return $pdf->download('laporan_pembayaran.pdf');
    }
}
