@extends('layouts.admin')

@section('title', 'Daftar Tagihan Pembayaran')

@section('content')
<div class="container mx-auto px-4 mt-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Tagihan Pembayaran</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded shadow border border-gray-300">
        <table class="min-w-full bg-white">
            <thead class="bg-blue-100 text-left">
                <tr>
                    <th class="py-3 px-4 border-b">#</th>
                    <th class="py-3 px-4 border-b">Nama Pembayaran</th>
                    <th class="py-3 px-4 border-b">Kelas</th>
                    <th class="py-3 px-4 border-b">Total</th>
                    <th class="py-3 px-4 border-b">Tanggal Jatuh Tempo</th>
                    <th class="py-3 px-4 border-b">Status</th>
                    <th class="py-3 px-4 border-b">Metode Pembayaran</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tagihans as $index => $pembayaran)
                    @php
                        // Ambil pivot data untuk user sekarang (jika ada)
                        $pivot = $pembayaran->siswa()
                                            ->where('user_id', Auth::id())
                                            ->first()
                                            ?->pivot;

                        // Jika belum pernah bayar, set default status & metode
                        $statusPivot = $pivot?->status ?? 'menunggu-pembayaran';
                        $metodePivot = $pivot?->metode ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        {{-- Penomoran cukup pakai $index + 1 --}}
                        <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>

                        <td class="py-2 px-4 border-b">{{ $pembayaran->nama }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kelas }}</td>
                        <td class="py-2 px-4 border-b">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                {{ $statusPivot == 'lunas' ? 'bg-green-200 text-green-800' : '' }}
                                {{ $statusPivot == 'menunggu-verifikasi' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                {{ $statusPivot == 'menunggu-pembayaran' ? 'bg-blue-200 text-blue-800' : '' }}
                                {{ $statusPivot == 'dibatalkan' ? 'bg-red-200 text-red-800' : '' }}
                                {{ !in_array($statusPivot, ['lunas','menunggu-verifikasi','menunggu-pembayaran','dibatalkan']) ? 'bg-gray-200 text-gray-800' : '' }}
                            ">
                                {{ ucfirst(str_replace('-', ' ', $statusPivot)) }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $metodePivot ? ucfirst(str_replace('-', ' ', $metodePivot)) : '-' }}
                        </td>
                        <td class="py-2 px-4 border-b space-x-2">
                            @if ($statusPivot == 'menunggu-pembayaran' || $statusPivot == 'dibatalkan')
                                <a href="{{ route('siswa.pembayaran.show', $pembayaran->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                   Lihat / Bayar
                                </a>
                            @elseif ($statusPivot == 'menunggu-verifikasi')
                                <span class="text-yellow-600 font-semibold text-sm">
                                    Menunggu Verifikasi
                                </span>
                            @else
                                <span class="text-gray-600 font-semibold text-sm">
                                    Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">
                            Tidak ada tagihan pembayaran untuk kelas Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Jika Anda tidak menggunakan paginate(), baris pagination ini bisa dihapus/ dikomentar --}}
    {{--
    <div class="mt-4">
        {{ $tagihans->withQueryString()->links() }}
    </div>
    --}}
</div>
@endsection
