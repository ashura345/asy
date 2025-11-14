@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mx-auto px-4 mt-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Pembayaran</h1>

    {{-- Toolbar opsional: tombol/aksi bisa ditambahkan di sini jika perlu --}}
    <div class="flex justify-between items-center mb-4">
        <div></div>
        <form action="{{ route('admin.kasir.index') }}" method="GET" class="flex items-center space-x-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, kelas, status..."
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:ring-blue-300"
            />
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                Cari
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded shadow border border-gray-300">
        <table class="min-w-full bg-white">
            <thead class="bg-blue-100 text-left">
                <tr>
                    <th class="py-3 px-4 border-b">Nama Pembayaran</th>
                    <th class="py-3 px-4 border-b">Nama Siswa</th>
                    <th class="py-3 px-4 border-b">Kelas</th>
                    <th class="py-3 px-4 border-b">Jumlah Pembayaran</th>
                    <th class="py-3 px-4 border-b">Tanggal Tempo</th>
                    <th class="py-3 px-4 border-b">Status</th>
                    <th class="py-3 px-4 border-b">Tanggal Bayar</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $pembayaran->nama_pembayaran ?? '-' }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->nama_siswa ?? 'Nama Tidak Ditemukan' }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kelas ?? '-' }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') }}</td>
                        <td class="py-2 px-4 border-b">
                            <span class="px-2 py-1 rounded text-white
                                @if($pembayaran->status == 'lunas') bg-green-500
                                @elseif($pembayaran->status == 'menunggu-verifikasi') bg-yellow-500
                                @else bg-red-500 @endif">
                                {{ ucfirst(str_replace('-', ' ', $pembayaran->status)) }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $pembayaran->tanggal_bayar
                                ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d-m-Y H:i')
                                : '-' }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(in_array($pembayaran->status, ['belum-lunas', 'menunggu-verifikasi']))
                                {{-- route dikoreksi sesuai catatan: admin.kasir.bayarForm --}}
                                <a href="{{ route('admin.kasir.bayarForm', $pembayaran->pivot_id) }}"
                                   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Bayar
                                </a>
                            @elseif($pembayaran->status == 'lunas')
                                <span class="text-gray-500">Lunas</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 px-4 text-center text-gray-500">
                            Tidak ada data pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $pembayarans->withQueryString()->links() }}
    </div>
</div>
@endsection
