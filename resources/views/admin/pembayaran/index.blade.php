@extends('layouts.app')

@section('content')
    <h1>Daftar Pembayaran Siswa</h1>
    <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kategori Pembayaran</th>
                <th>Jumlah Tagihan</th>
                <th>Status</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembayaran as $pembayaran)
                <tr>
                    <td>{{ $pembayaran->siswa->nama }}</td>
                    <td>{{ $pembayaran->kategoriPembayaran->nama_kategori }}</td>
                    <td>{{ $pembayaran->jumlah_tagihan }}</td>
                    <td>{{ ucfirst($pembayaran->status) }}</td>
                    <td>{{ $pembayaran->tanggal_jatuh_tempo }}</td>
                    <td>
                        <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
