@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
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
                'start_date'      => $start,
                'end_date'        => $end,
                'kelas'           => $kelasFilter,
                'pembayaran_id'   => $pembayaranFilter,
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

    {{-- FORM FILTER --}}
    <form action="{{ route('admin.laporan.index') }}" method="GET"
          class="mb-6 flex flex-wrap items-end gap-4">
        {{-- Mode tetap --}}
        <input type="hidden" name="mode" value="{{ $mode }}">

        {{-- Filter tanggal (terutama untuk mode lunas) --}}
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $start) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $end) }}"
                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Filter khusus mode TUNGGAKAN --}}
        @if($mode === 'tunggakan')
            <div>
                <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas" id="kelas"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $kls)
                        <option value="{{ $kls }}" {{ $kelasFilter == $kls ? 'selected' : '' }}>
                            {{ $kls }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="pembayaran_id" class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
                <select name="pembayaran_id" id="pembayaran_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Pembayaran</option>
                    @foreach($daftarPembayaran as $p)
                        <option value="{{ $p->id }}" {{ $pembayaranFilter == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Filter
            </button>
        </div>

        {{-- Export hanya mode lunas --}}
        @if($mode === 'lunas')
            <div class="mt-6 flex gap-2">
                <a href="{{ route('admin.laporan.export.excel', ['start_date' => $start, 'end_date' => $end]) }}"
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    Export ke Excel
                </a>
                <a href="{{ route('admin.laporan.export.pdf', ['start_date' => $start, 'end_date' => $end]) }}"
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md">
                    Export ke PDF
                </a>
            </div>
        @endif
    </form>

    {{-- TABEL --}}
    @if($laporans->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p>
                @if($mode === 'tunggakan')
                    Tidak ada data tunggakan dengan filter ini.
                @else
                    Tidak ada pembayaran yang sudah lunas dengan filter ini.
                @endif
            </p>
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

                        @if($mode === 'lunas')
                            <th class="py-2 px-4 border-b text-left">Tanggal Bayar</th>
                            <th class="py-2 px-4 border-b text-left">Metode</th>
                        @else
                            <th class="py-2 px-4 border-b text-left">Status</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        @endif
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

                            @if($mode === 'lunas')
                                <td class="py-2 px-4 border-b">
                                    {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y H:i') }}
                                </td>
                                <td class="py-2 px-4 border-b capitalize">{{ $item->metode }}</td>
                            @else
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
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
