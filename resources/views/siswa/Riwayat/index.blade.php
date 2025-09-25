@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Riwayat Pembayaran</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($riwayat->isEmpty())
        <p class="text-gray-600">Belum ada riwayat pembayaran.</p>
    @else
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama Pembayaran</th>
                    <th class="py-2 px-4 border-b text-left">Total Tagihan</th>
                    <th class="py-2 px-4 border-b text-left">Jumlah Bayar</th>
                    <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">
                            {{ $item->nama_pembayaran }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y H:i') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="px-2 py-1 rounded 
                                @if($item->status == 'lunas') bg-green-500 
                                @elseif($item->status == 'menunggu-verifikasi') bg-yellow-500 
                                @else bg-red-500 @endif
                                text-white">
                                {{ ucfirst(str_replace('-', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{-- Link ke halaman cetak (print preview) --}}
                            <a href="{{ route('riwayat.cetak', $item->id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Cetak
                            </a>
                            {{-- Link ke halaman cetak PDF (buka di tab baru) --}}
                            <a href="{{ route('riwayat.cetak.pdf', $item->id) }}" 
                               class="ml-2 bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm" 
                               target="_blank">
                                PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
