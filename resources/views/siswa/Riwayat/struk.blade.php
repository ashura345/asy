@extends('layouts.admin')

@section('title', 'Struk Pembayaran')

@section('content')
<div class="container">
    <h2 class="mt-3 mb-4">Struk Pembayaran</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Nama Siswa:</strong> {{ $data->nama_siswa }}</p>
            <p><strong>Kelas:</strong> {{ $data->kelas }}</p>
            <p><strong>Nama Pembayaran:</strong> {{ $data->nama_pembayaran }}</p>
            <p><strong>Total Tagihan:</strong> {{ number_format($data->total_tagihan, 0, ',', '.') }}</p>
            <p><strong>Jumlah Bayar:</strong> {{ number_format($data->jumlah_bayar, 0, ',', '.') }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ date('d/m/Y H:i', strtotime($data->tanggal_bayar)) }}</p>
            <p><strong>Metode:</strong> {{ ucfirst($data->metode) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($data->status) }}</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('siswa.riwayat.pdf', $data->id) }}" class="btn btn-primary" target="_blank">Download PDF</a>
        <a href="{{ route('siswa.riwayat.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection