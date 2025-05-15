@extends('layouts.app')

@section('content')
    <h1>Laporan Pembayaran</h1>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Kategori Pembayaran</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $pembayaran)
                <tr>
                    <td>{{ $pembayaran->siswa->nama }}</td>
                    <td>{{ $pembayaran->kategoriPembayaran->nama }}</td>
                    <td>{{ $pembayaran->nominal }}</td>
                    <td>{{ ucfirst($pembayaran->status) }}</td>
                    <td>
                        <a href="{{ route('admin.laporan.cetak', $pembayaran->id) }}" class="btn btn-success btn-sm">Cetak</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
