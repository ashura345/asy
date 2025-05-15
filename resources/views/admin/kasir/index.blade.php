@extends('layouts.app')

@section('content')
    <h1>Daftar Pembayaran Kasir</h1>
    <a href="{{ route('admin.kasir.create') }}" class="btn btn-primary mb-3">Tambah Pembayaran</a>

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
                        <form action="{{ route('admin.kasir.destroy', $pembayaran->id) }}" method="POST" style="display:inline;">
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
