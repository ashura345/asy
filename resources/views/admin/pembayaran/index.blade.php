@extends('layouts.admin')

@section('title', 'Daftar Pembayaran')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Daftar Pembayaran</h1>

    {{-- Toolbar responsif: tombol tambah + form pencarian & filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <a href="{{ route('admin.pembayaran.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md shadow-md">
            + Tambah Pembayaran
        </a>

        {{-- Form Pencarian & Filter --}}
        <form action="{{ route('admin.pembayaran.index') }}" method="GET" id="filterPembayaranForm" class="flex flex-wrap items-center gap-4">
            {{-- Filter Kategori --}}
            <select name="kategori" id="kategoriSelect" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Semua Kategori</option>
                @isset($kategoriList)
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                @endisset
            </select>

            {{-- Filter Kelas (diurutkan: kosong dulu, lalu terisi A–Z) --}}
            @php
                $sortedKelasList = collect($kelasList ?? [])->sort(function ($a, $b) {
                    $aEmpty = empty($a);
                    $bEmpty = empty($b);

                    if ($aEmpty && !$bEmpty) return -1;   // a kosong, b tidak -> a dulu
                    if (!$aEmpty && $bEmpty) return 1;    // b kosong, a tidak -> b dulu

                    return strcmp($a ?? '', $b ?? '');    // sama-sama isi -> urut alfabet
                });
            @endphp

            <select name="kelas" id="kelasSelect" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Semua Kelas</option>
                @foreach($sortedKelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                        {{ $kelas ?: 'Tidak ada kelas' }}
                    </option>
                @endforeach
            </select>

            {{-- Input Pencarian --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, kelas, kategori..."
                class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"
            />
            
            {{-- Tombol Cari --}}
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                Cari
            </button>

            {{-- Reset Filter --}}
            @if(request()->filled('search') || request()->filled('kategori') || request()->filled('kelas'))
                <a href="{{ route('admin.pembayaran.index') }}" class="px-3 py-2 border rounded-md text-gray-700">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Menampilkan pesan sukses --}}
    @if(session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @php
        // Urutkan data di tabel: tanpa kelas dulu, kemudian kelas terisi A–Z
        $sortedCollection = $pembayarans->getCollection()
            ->sort(function ($a, $b) {
                $aEmpty = empty($a->kelas);
                $bEmpty = empty($b->kelas);

                if ($aEmpty && !$bEmpty) return -1;
                if (!$aEmpty && $bEmpty) return 1;

                return strcmp($a->kelas ?? '', $b->kelas ?? '');
            })
            ->values(); // reset index ke 0,1,2...

        $pembayarans->setCollection($sortedCollection);
    @endphp

    {{-- Tabel Daftar Pembayaran --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
            <thead class="bg-blue-100 text-left">
                <tr>
                    <th class="py-3 px-4 border-b">#</th>
                    <th class="py-3 px-4 border-b">Nama Pembayaran</th>
                    <th class="py-3 px-4 border-b">Kategori</th>
                    <th class="py-3 px-4 border-b">Kelas</th>
                    <th class="py-3 px-4 border-b">Jumlah</th>
                    <th class="py-3 px-4 border-b">Tanggal Buat</th>
                    <th class="py-3 px-4 border-b">Tanggal Tempo</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        {{-- Nomor urut ikut urutan tabel + pagination --}}
                        <td class="py-2 px-4 border-b">
                            {{ $pembayarans->firstItem() + $loop->index }}
                        </td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->nama }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kategori->nama ?? '-' }}</td>
                        <td class="py-2 px-4 border-b">{{ $pembayaran->kelas ?? 'Tidak ada kelas' }}</td>
                        <td class="py-2 px-4 border-b text-right">
                            Rp {{ number_format($pembayaran->jumlah, 2, ',', '.') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_buat)->format('d-m-Y') }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            {{ $pembayaran->tanggal_tempo ? \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="py-2 px-4 border-b space-x-2">
                            <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">Tidak ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $pembayarans->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script>
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kelasSelect = document.getElementById('kelasSelect');
    const filterPembayaranForm = document.getElementById('filterPembayaranForm');

    const submitFilter = () => {
        filterPembayaranForm.submit();
    };

    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', submitFilter);
    }

    if (kelasSelect) {
        kelasSelect.addEventListener('change', submitFilter);
    }
</script>
@endpush

@endsection
