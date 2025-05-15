@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Riwayat Pembayaran</h2>

    <table class="min-w-full bg-white border rounded-lg shadow-md">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">Tanggal</th>
                <th class="px-4 py-2 border">Kategori</th>
                <th class="px-4 py-2 border">Jumlah</th>
                <th class="px-4 py-2 border">Metode</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $pembayaran)
            <tr>
                <td class="px-4 py-2 border">{{ $pembayaran->tanggal_pembayaran }}</td>
                <td class="px-4 py-2 border">{{ $pembayaran->kategori }}</td>
                <td class="px-4 py-2 border">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                <td class="px-4 py-2 border">{{ ucfirst($pembayaran->metode) }}</td>
                <td class="px-4 py-2 border">{{ $pembayaran->status_pembayaran }}</td>
                <td class="px-4 py-2 border">
                    <a href="{{ route('riwayat.cetak', $pembayaran->id) }}" target="_blank"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                        Cetak Struk
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">Belum ada riwayat pembayaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
