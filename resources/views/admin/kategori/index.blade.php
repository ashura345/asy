@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Manajemen Kategori Pembayaran</h2>
        <a href="{{ route('admin.kategori.create') }}" class="bg-emerald-600 text-white py-2 px-4 rounded hover:bg-emerald-700">Tambah Kategori</a>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Daftar Kategori Pembayaran -->
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left border-b">No</th>
                <th class="px-4 py-2 text-left border-b">Nama Kategori</th>
                <th class="px-4 py-2 text-left border-b">Deskripsi</th>
                <th class="px-4 py-2 text-left border-b">Tipe Pembayaran</th>
                <th class="px-4 py-2 text-left border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoriPembayaran as $key => $kategori)
                <tr>
                    <td class="px-4 py-2 border-b">{{ $key + 1 }}</td>
                    <td class="px-4 py-2 border-b">{{ $kategori->nama_kategori }}</td>
                    <td class="px-4 py-2 border-b">{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                    <td class="px-4 py-2 border-b">{{ ucfirst($kategori->tipe_pembayaran) }}</td>
                    <td class="px-4 py-2 border-b">
                        <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a> | 
                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection