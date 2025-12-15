@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    {{-- JUDUL --}}
    <h1 class="text-2xl font-bold mb-4">
        Laporan Pembayaran Siswa
        <span class="text-sm font-normal text-gray-500">
            ({{ $mode === 'tunggakan' ? 'Tunggakan' : 'Sudah Lunas' }})
        </span>
    </h1>

    {{-- TOGGLE MODE SIANG / MALAM --}}
    <div class="mb-4 flex items-center gap-3">
        <span class="text-sm text-gray-600">Mode tampilan:</span>

        @php
            $baseQuery = [
                'start_date'    => $start,
                'end_date'      => $end,
                'kelas'         => $kelasFilter,
                'pembayaran_id' => $pembayaranFilter,
            ];
        @endphp

        <div class="inline-flex bg-gray-200 rounded-full p-1 shadow-inner">
            {{-- Sudah Lunas (Siang) --}}
            <a href="{{ route('admin.laporan.index', array_merge($baseQuery, ['mode' => 'lunas'])) }}"
               class="flex items-center gap-2 px-4 py-1.5 text-xs md:text-sm rounded-full transition
                      @if($mode === 'lunas')
                          bg-yellow-400 text-gray-900 shadow
                      @else
                          text-gray-600 hover:bg-gray-300
                      @endif">
                <i class="fa fa-sun"></i>
                <span>Sudah Lunas</span>
            </a>

            {{-- Tunggakan (Malam) --}}
            <a href="{{ route('admin.laporan.index', array_merge($baseQuery, ['mode' => 'tunggakan'])) }}"
               class="flex items-center gap-2 px-4 py-1.5 text-xs md:text-sm rounded-full transition
                      @if($mode === 'tunggakan')
                          bg-indigo-600 text-white shadow
                      @else
                          text-gray-600 hover:bg-gray-300
                      @endif">
                <i class="fa fa-moon"></i>
                <span>Tunggakan</span>
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM FILTER KHUSUS TUNGGAKAN --}}
    <form id="filterTunggakanForm"
          action="{{ route('admin.laporan.index') }}"
          method="GET"
          class="mb-6 flex flex-wrap items-end gap-4">

        {{-- Paksa mode tunggakan --}}
        <input type="hidden" name="mode" value="tunggakan">

        {{-- Filter Kelas --}}
        <div>
            <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="kelas" id="kelas"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="document.getElementById('filterTunggakanForm').submit();">
                <option value="">Semua Kelas</option>
                @foreach($daftarKelas as $kls)
                    <option value="{{ $kls }}" {{ ($kelasFilter == $kls) ? 'selected' : '' }}>
                        {{ $kls }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Jenis Pembayaran --}}
        <div>
            <label for="pembayaran_id" class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
            <select name="pembayaran_id" id="pembayaran_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="document.getElementById('filterTunggakanForm').submit();">
                <option value="">Semua Pembayaran</option>
                @foreach($daftarPembayaran as $p)
                    <option value="{{ $p->id }}" {{ ($pembayaranFilter == $p->id) ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol ini opsional, bisa kamu hapus kalau mau benar-benar full auto --}}
        <div class="mt-6">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                Filter
            </button>
        </div>
    </form>

    {{-- TABEL TUNGGAKAN --}}
    @if($laporans->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p>Tidak ada data tunggakan dengan filter ini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">No</th>
                        <th class="py-2 px-4 border-b text-left">Kelas</th>
                        <th class="py-2 px-4 border-b text-left">Nama Siswa</th>
                        <th class="py-2 px-4 border-b text-left">Nama Pembayaran</th>
                        <th class="py-2 px-4 border-b text-right">Jumlah (Rp)</th>
                        <th class="py-2 px-4 border-b text-left">Status</th>
                        <th class="py-2 px-4 border-b text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $index => $item)
                        <tr class="@if($loop->even) bg-gray-50 @endif">
                            <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->kelas }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->nama_siswa }}</td>
                            <td class="py-2 px-4 border-b">{{ $item->nama_pembayaran }}</td>
                            <td class="py-2 px-4 border-b text-right">
                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-4 border-b capitalize">
                                {{ $item->status ?? 'belum bayar' }}
                            </td>
                            <td class="py-2 px-4 border-b">
                                <form action="{{ route('admin.laporan.kirimEmail', $item->pembayaran_user_id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Kirim notifikasi email ke {{ $item->nama_siswa }}?');">
                                    @csrf
                                    <button type="submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-3 py-1 rounded-md">
                                        Kirim Email
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
