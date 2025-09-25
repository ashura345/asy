@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Struk Pembayaran</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <p><strong>Nama Siswa:</strong> {{ $data->nama_siswa }}</p>
        <p><strong>Kelas:</strong> {{ $data->kelas }}</p>
        <p><strong>Nama Pembayaran:</strong> {{ $data->nama_pembayaran }}</p>
        <p><strong>Jumlah Bayar:</strong> Rp {{ number_format($data->jumlah_bayar, 0, ',', '.') }}</p>
        <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d-m-Y H:i') }}</p>
        <p><strong>Metode:</strong> {{ ucfirst($data->metode) }}</p>

        <div class="mt-6 flex gap-4">
            <button onclick="window.print()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Print
            </button>
            <a href="{{ route('riwayat.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Kembali
            </a>
            <a href="{{ route('riwayat.cetak.pdf', $data->pembayaran_id) }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded" target="_blank">
                Unduh PDF
            </a>
        </div>
    </div>
</div>
@endsection
