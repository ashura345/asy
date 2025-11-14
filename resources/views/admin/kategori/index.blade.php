@extends('layouts.admin')

@section('title', 'Manajemen Kategori Pembayaran')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Kategori Pembayaran</h1>

    {{-- Toolbar: tombol tambah + form pencarian --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <a href="{{ route('admin.kategori.create') }}"
           class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-4 rounded">
           + Tambah Kategori
        </a>

        <form action="{{ route('admin.kategori.index') }}" method="GET"
              class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari kategori..."
                   class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring focus:ring-blue-300" />

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                Cari
            </button>

            @if(request()->filled('search'))
                <a href="{{ route('admin.kategori.index') }}" class="px-3 py-1 border rounded text-gray-700">
                    Reset
                </a>
            @endif
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
                    <th class="py-2 px-4 border-b text-left">No</th>
                    <th class="py-2 px-4 border-b text-left">Nama Kategori</th>
                    <th class="py-2 px-4 border-b text-left">Deskripsi</th>
                    <th class="py-2 px-4 border-b text-left">Tipe Pembayaran</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategori as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">
                            @if(method_exists($kategori, 'firstItem'))
                                {{ $kategori->firstItem() + $key }}
                            @else
                                {{ $key + 1 }}
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">{{ $item->nama }}</td>
                        <td class="py-2 px-4 border-b">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                        <td class="py-2 px-4 border-b">{{ $item->tipe }}</td>
                        <td class="py-2 px-4 border-b space-x-2">
                            <a href="{{ route('admin.kategori.edit', $item->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                               Edit
                            </a>
                            <form action="{{ route('admin.kategori.destroy', $item->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Yakin ingin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">
                            Tidak ada data kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination (aktif jika $kategori adalah LengthAwarePaginator) --}}
    @if(method_exists($kategori, 'links'))
        <div class="mt-4">
            {{ request()->has('search') ? $kategori->withQueryString()->links() : $kategori->links() }}
        </div>
    @endif
</div>
@endsection
