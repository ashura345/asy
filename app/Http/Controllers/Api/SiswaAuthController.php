<?php

// app/Http/Controllers/Api/SiswaAuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class SiswaAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa || !Hash::check($request->password, $siswa->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIS atau password salah.'
            ], 401);
        }

        $token = $siswa->createToken('siswa_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'siswa' => $siswa,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.'
        ]);
    }
}

