<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
        public function login(Request $request){
        $request->validate([
            'nis' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nis', $request->nis)
            ->where('role', 'siswa') // hanya untuk siswa
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIS atau password salah',
            ], 401);
        }

        // Hapus token lama (optional, biar 1 login 1 device)
        $user->tokens()->delete();

        // Buat token baru untuk siswa
        $token = $user->createToken('mobile_siswa_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nis' => $user->nis,
                'kelas' => $user->kelas,
                'tahun_ajaran' => $user->tahun_ajaran,
            ],
        ]);
    }


    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => $request->user(),
        ]);
    }
}
