@extends('layouts.admin')
@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-6">Riwayat Pembayaran Saya</h1>

    @if($riwayatBayar->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
            <p class="font-bold">Belum ada pembayaran yang lunas</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">#</th>
                        <th class="px-4 py-2 border-b text-left">Nama Pembayaran</th>
                        <th class="px-4 py-2 border-b text-left">Jumlah</th>
                        <th class="px-4 py-2 border-b text-left">Tanggal Bayar</th>
                        <th class="px-4 py-2 border-b text-left">Metode</th>
                        <th class="px-4 py-2 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatBayar as $index => $row)
                        <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border-b">{{ $row->nama_pembayaran }}</td>
                            <td class="px-4 py-2 border-b">
                                Rp {{ number_format($row->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 border-b">
                                {{ \Carbon\Carbon::parse($row->tanggal_bayar)->format('d-m-Y H:i') }}
                            </td>
                            <td class="px-4 py-2 border-b">{{ ucfirst($row->metode) }}</td>
                            <td class="px-4 py-2 border-b text-center">
                                <a href="{{ route('siswa.riwayat.struk', $row->pembayaran_id) }}"
                                   target="_blank"
                                   class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                    Cetak Struk
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('siswa.dashboard') }}" class="text-blue-600 hover:underline">
            &laquo; Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
