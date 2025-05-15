@extends('layouts.app')

@section('content')
<div class="p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Siswa</h2>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-2xl font-semibold mb-4 text-blue-600">Daftar Siswa yang Sudah Membayar</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 border-b">No</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 border-b">Nama Siswa</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 border-b">Kelas</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 border-b">Tanggal Bayar</th>
                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 border-b">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($siswaBayar as $index => $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-2 border-b">{{ $index + 1 }}</td>
                        <td class="px-5 py-2 border-b">{{ $siswa->name }}</td>
                        <td class="px-5 py-2 border-b">{{ $siswa->kelas }}</td>
                        <td class="px-5 py-2 border-b">{{ \Carbon\Carbon::parse($siswa->tanggal_pembayaran)->format('d M Y') }}</td>
                        <td class="px-5 py-2 border-b">
                            @if($siswa->status_pembayaran === 'lunas')
                                <span class="text-green-600 font-semibold">Lunas</span>
                            @else
                                <span class="text-red-500 font-semibold">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-4 text-center text-gray-500">Belum ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
