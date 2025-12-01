<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\KategoriPembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // Fungsi untuk menampilkan daftar pembayaran
    public function index(Request $request)
    {
        // Menyiapkan query dasar untuk mengambil pembayaran bersama kategori dan siswa
        $query = Pembayaran::with('kategori', 'siswa');

        // ------ Filter berdasarkan pencarian umum: nama/kategori/kelas ------
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($k) use ($search) {
                      $k->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // ------ Filter berdasarkan kategori ------
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // ------ Filter berdasarkan kelas ------
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Menyiapkan data untuk dropdown
        // Ambil daftar kategori pembayaran untuk ditampilkan di filter kategori
        $kategoriList = KategoriPembayaran::select('id', 'nama')->get();

        // Ambil daftar kelas dari pengguna dengan role siswa
        $kelasList = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->select('kelas')->distinct()->orderBy('kelas')
            ->pluck('kelas');

        // Mengambil data pembayaran dengan pagination dan mengurutkan berdasarkan waktu terbaru
        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(10)
            ->appends($request->query()); // mempertahankan query saat pagination

        // Kembalikan ke view dengan data yang dibutuhkan
        return view('admin.pembayaran.index', compact('pembayarans', 'kategoriList', 'kelasList'));
    }

    // Fungsi untuk menampilkan halaman form tambah pembayaran
    public function create()
    {
        // Mengambil semua kategori pembayaran untuk dropdown
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.create', compact('kategori'));
    }

    // Fungsi untuk menyimpan data pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'    => 'required|exists:kategori_pembayarans,id',
            'nama'           => 'required|string|max:255',
            'kelas'          => 'nullable|string|max:255',
            'jumlah'         => 'required|numeric',
            'tanggal_buat'   => 'required|date',
            'tanggal_tempo'  => 'nullable|date',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Format jumlah menjadi dua desimal tanpa format ribuan
        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        // Jika ada foto, simpan foto ke storage
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        // Membuat pembayaran baru
        $pembayaran = Pembayaran::create($validated);

        // Ambil semua siswa yang sesuai dengan kelas (jika ada) untuk diberikan pembayaran
        $siswas = User::where('role', 'siswa')
            ->when($pembayaran->kelas, fn($q) => $q->where('kelas', $pembayaran->kelas))
            ->get();

        // Attach siswa ke pembayaran
        $attachData = $siswas->mapWithKeys(fn($siswa) => [
            $siswa->id => [
                'status' => 'belum lunas',
                'tanggal_pembayaran' => null,
                'order_id' => null,
            ]
        ])->toArray();

        // Hubungkan siswa ke pembayaran
        $pembayaran->siswa()->attach($attachData);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan dan ditugaskan ke siswa.');
    }

    // Fungsi untuk menampilkan form edit pembayaran
    public function edit(Pembayaran $pembayaran)
    {
        // Ambil semua kategori pembayaran
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'kategori'));
    }

    // Fungsi untuk memperbarui data pembayaran
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'kategori_id'    => 'required|exists:kategori_pembayarans,id',
            'nama'           => 'required|string|max:255',
            'kelas'          => 'nullable|string|max:255',
            'jumlah'         => 'required|numeric',
            'tanggal_buat'   => 'required|date',
            'tanggal_tempo'  => 'nullable|date',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Format jumlah menjadi dua desimal tanpa format ribuan
        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        // Jika ada foto baru, hapus foto lama dan simpan foto baru
        if ($request->hasFile('foto')) {
            if ($pembayaran->foto) {
                Storage::delete($pembayaran->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        // Update data pembayaran
        $pembayaran->update($validated);

        // Hapus semua data siswa yang terkait dan tambahkan lagi sesuai kelas yang dipilih
        $pembayaran->siswa()->detach();

        $siswas = User::where('role', 'siswa')
            ->when($pembayaran->kelas, fn($q) => $q->where('kelas', $pembayaran->kelas))
            ->get();

        $attachData = $siswas->mapWithKeys(fn($siswa) => [
            $siswa->id => [
                'status' => 'belum lunas',
                'tanggal_pembayaran' => null,
                'order_id' => null,
            ]
        ])->toArray();

        $pembayaran->siswa()->attach($attachData);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil diperbarui dan siswa diupdate.');
    }

    // Fungsi untuk menghapus data pembayaran
    public function destroy(Pembayaran $pembayaran)
    {
        // Hapus foto jika ada
        if ($pembayaran->foto) {
            Storage::delete($pembayaran->foto);
        }

        // Hapus relasi siswa
        $pembayaran->siswa()->detach();
        
        // Hapus data pembayaran
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
