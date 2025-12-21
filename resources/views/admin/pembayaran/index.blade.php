@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Pembayaran</h1>

    {{-- Toolbar --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <a href="{{ route('admin.pembayaran.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            + Tambah Pembayaran
        </a>

        <form action="{{ route('admin.pembayaran.index') }}" method="GET"
              id="filterForm" class="flex flex-wrap items-center gap-2">

            {{-- Search --}}
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / kelas / kategori"
                   class="border border-gray-300 rounded px-3 py-1
                          focus:outline-none focus:ring focus:ring-blue-300" />

            {{-- Filter Kategori --}}
            <select name="kategori" id="kategoriSelect"
                    class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}"
                        {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Kelas --}}
            <select name="kelas" id="kelasSelect"
                    class="border border-gray-300 rounded px-3 py-1">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}"
                        {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelas ?: 'Tanpa Kelas' }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded">
                Cari
            </button>

            @if(request()->hasAny(['search','kategori','kelas']))
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="px-3 py-1 border rounded text-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded shadow-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left">Nama</th>
                    <th class="py-2 px-4 border-b text-left">Kategori</th>
                    <th class="py-2 px-4 border-b text-left">Kelas</th>
                    <th class="py-2 px-4 border-b text-right">Jumlah</th>
                    <th class="py-2 px-4 border-b text-left">Tanggal Buat</th>
                    <th class="py-2 px-4 border-b text-left">Jatuh Tempo</th>
                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayarans as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->nama }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->kategori->nama ?? '-' }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->kelas ?? 'Semua' }}
                    </td>
                    <td class="py-2 px-4 border-b text-right">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->created_at->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        {{ $pembayaran->tanggal_tempo
                            ? \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->translatedFormat('d M Y')
                            : '-' }}
                    </td>
                    <td class="py-2 px-4 border-b space-x-2">
                        <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}"
                              method="POST" class="inline-block"
                              onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7"
                        class="py-4 text-center text-gray-500">
                        Tidak ada data pembayaran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $pembayarans->links() }}
    </div>
</div>

{{-- Script --}}
@push('scripts')
<script>
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kelasSelect = document.getElementById('kelasSelect');
    const filterForm = document.getElementById('filterForm');

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', () => filterForm.submit());
    }

    if (kelasSelect) {
        kelasSelect.addEventListener('change', () => filterForm.submit());
    }
</script>
@endpush

@endsection
