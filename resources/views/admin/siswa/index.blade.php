@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Siswa</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.siswa.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">+ Tambah Siswa</a>
        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex items-center space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS / kelas"
                class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:ring-blue-300" />
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                Cari
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama</th>
                    <th class="py-2 px-4 border-b text-left">NIS</th>
                    <th class="py-2 px-4 border-b text-left">Kelas</th>
                    <th class="py-2 px-4 border-b text-left">Email</th>
                    <th class="py-2 px-4 border-b text-left">Tahun Ajaran</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswas as $siswa)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $siswa->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->nis }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->kelas }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->email }}</td>
                    <td class="py-2 px-4 border-b">{{ $siswa->tahun_ajaran }}</td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
