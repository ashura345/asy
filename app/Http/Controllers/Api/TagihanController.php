<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tagihan;

class TagihanController extends Controller
{
    public function getSummary($nis)
    {
        $user = User::where('nis', $nis)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $tagihanAktif = Tagihan::aktif()->where('siswa_id', $user->id)->count();
        $tagihanTunggakan = Tagihan::tunggakan()->where('siswa_id', $user->id)->count();

        return response()->json([
        'status' => 'success',
        'message' => 'Data tagihan berhasil diambil',
        'data' => [
        'tagihan_aktif' => $tagihanAktif,
        'tunggakan' => $tagihanTunggakan,
        ]
    ]);
    }
}
