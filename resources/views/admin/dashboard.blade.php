@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6">Ringkasan</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded shadow-md">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-lg">Total Siswa</h3>
                <select id="filterKelas" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasOptions as $k)
                        <option value="{{ $k }}">Kelas {{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <p id="totalSiswaText" class="text-3xl font-bold">
                {{ number_format($totalSiswa, 0, ',', '.') }}
            </p>
            <a id="linkManajemenSiswa" href="{{ route('admin.siswa.index') }}" class="inline-block text-blue-600 text-xs mt-2">Buka Manajemen Siswa ➜</a>
            <div class="text-xs text-gray-500 mt-1">Angka berubah sesuai pilihan kelas</div>
        </div>

        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Nominal Lunas (MTD)</h3>
            <p class="text-2xl font-bold">
                Rp {{ number_format($nominalLunas, 0, ',', '.') }}
            </p>
            <div class="text-xs text-gray-500 mt-1">Akumulasi uang masuk bulan berjalan</div>
        </div>

        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Outstanding</h3>
            <p class="text-2xl font-bold text-red-600">
                Rp {{ number_format($nominalOutstanding, 0, ',', '.') }}
            </p>
            <div class="text-xs text-gray-500 mt-1">Tagihan yang belum dibayar</div>
        </div>

        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Transaksi (MTD)</h3>
            <p class="text-2xl font-bold">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
            <div class="text-xs text-gray-500 mt-1">Semua status bulan berjalan</div>
        </div>
    </div>

    {{-- Grafik Pembayaran per Bulan --}}
    <div class="bg-white p-6 rounded shadow-md mb-6">
        <h3 class="font-semibold text-lg mb-2">Total Pembayaran per Bulan — {{ $year }}</h3>
        <div style="height:320px"><canvas id="lineChart"></canvas></div>
    </div>

    {{-- Pengingat Jatuh Tempo --}}
    <div class="bg-white p-6 rounded shadow-md">
        <h3 class="font-semibold text-lg">Pengingat Jatuh Tempo Pembayaran</h3>
        <p>{{ number_format($transaksiJatuhTempo, 0, ',', '.') }} transaksi jatuh tempo (≤ hari ini).</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Grafik pembayaran bulanan
    const months = @json($months);
    const totals = @json($totals);

    new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Total Pembayaran (Rp)',
                data: totals,
                borderWidth: 3,
                fill: true,
                tension: .35
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            scales:{
                y:{ beginAtZero:true, ticks:{ callback:v=>'Rp '+Number(v).toLocaleString('id-ID')}}
            },
            plugins:{
                tooltip:{ callbacks:{ label:c=>'Rp '+Number(c.parsed.y).toLocaleString('id-ID')}}
            }
        }
    });
</script>
@endpush
