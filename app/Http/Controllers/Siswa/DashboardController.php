<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data pembayaran yang statusnya sudah lunas dan milik siswa yang sedang login
        $siswaBayar = Pembayaran::where('user_id', Auth::id())
            ->where('status_pembayaran', 'Lunas')
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        return view('siswa.dashboard', compact('siswaBayar'));
    }
}
