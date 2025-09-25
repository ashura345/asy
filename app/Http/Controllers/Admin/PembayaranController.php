<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\KategoriPembayaran;
use App\Models\User; // Import User agar lebih jelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('kategori', 'siswa');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('kategori', function ($k) use ($search) {
                      $k->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $pembayarans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.create', compact('kategori'));
    }

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

        // Format jumlah dengan 2 desimal tanpa format ribuan
        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        $pembayaran = Pembayaran::create($validated);

        // Ambil siswa sesuai kelas, jika kosong maka semua siswa
        $siswas = User::where('role', 'siswa')
            ->when($pembayaran->kelas, fn($q) => $q->where('kelas', $pembayaran->kelas))
            ->get();

        // Attach pivot siswa ke pembayaran
        $attachData = $siswas->mapWithKeys(fn($siswa) => [
            $siswa->id => [
                'status' => 'belum lunas',
                'tanggal_pembayaran' => null,
                'order_id' => null,
            ]
        ])->toArray();

        $pembayaran->siswa()->attach($attachData);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil ditambahkan dan ditugaskan ke siswa.');
    }

    public function edit(Pembayaran $pembayaran)
    {
        $kategori = KategoriPembayaran::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'kategori'));
    }

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

        $validated['jumlah'] = number_format((float) $validated['jumlah'], 2, '.', '');

        if ($request->hasFile('foto')) {
            if ($pembayaran->foto) {
                Storage::delete($pembayaran->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pembayaran');
        }

        $pembayaran->update($validated);

        // Update pivot siswa: detach dulu baru attach ulang sesuai kelas
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

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->foto) {
            Storage::delete($pembayaran->foto);
        }

        $pembayaran->siswa()->detach();
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
