@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container">
    <h1 class="mt-3 mb-4">Riwayat Pembayaran</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th style="width: 5%;">#</th>
                <th>Pembayaran</th>
                <th>Total Tagihan</th>
                <th>Jumlah Bayar</th>
                <th>Tanggal Bayar</th>
                <th>Metode</th>
                <th>Status</th>
                <th style="width: 20%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_pembayaran }}</td>
                <td>{{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                <td>{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($item->tanggal_bayar)) }}</td>
                <td>{{ ucfirst($item->metode) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>
                    <a href="{{ route('siswa.riwayat.cetak', $item->id) }}" class="btn btn-sm btn-primary">Lihat Struk</a>
                    <a href="{{ route('siswa.riwayat.pdf', $item->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada riwayat pembayaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection