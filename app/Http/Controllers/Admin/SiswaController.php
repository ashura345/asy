<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
{
    // Tampilkan daftar siswa dengan fitur pencarian & pagination
    public function index(Request $request)
    {
        $query = User::where('role', 'siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('name')->paginate(10); // Pagination 10 data per halaman
        return view('admin.siswa.index', compact('siswas'));
    }

    // Form tambah siswa
    public function create()
    {
        return view('admin.siswa.create');
    }

    // Simpan siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email',
            'nis' => 'nullable|unique:users,nis',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'role' => $request->role ?? 'siswa',
            'tahun_ajaran' => $request->tahun_ajaran,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    // Form edit siswa
    public function edit(User $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    // Update data siswa
    public function update(Request $request, User $siswa)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $siswa->id,
            'nis' => 'nullable|unique:users,nis,' . $siswa->id,
        ]);

        $siswa->update([
            'name' => $request->name,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'role' => $request->role ?? 'siswa',
            'tahun_ajaran' => $request->tahun_ajaran,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $siswa->password,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    // Hapus siswa
    public function destroy(User $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
