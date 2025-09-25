@extends('layouts.admin')

@section('content')
<h2>Riwayat Pembayaran</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>Nama Pembayaran</th>
            <th>Jumlah</th>
            <th>Tanggal Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($riwayats as $riwayat)
        <tr>
            <td>{{ $riwayat->nama_pembayaran }}</td>
            <td>Rp {{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
            <td>{{ $riwayat->tanggal_pembayaran->format('d M Y H:i') }}</td>
            <td>{{ ucfirst($riwayat->metode_pembayaran) }}</td>
            <td>{{ ucfirst($riwayat->status_pembayaran) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
