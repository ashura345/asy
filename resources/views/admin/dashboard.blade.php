
@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Statistik Pembayaran dan Grafik -->
    <h2 class="text-2xl font-bold mb-4">Statistik Pembayaran</h2>
    <div class="bg-white p-6 rounded shadow-md">
        <canvas id="lineChart"></canvas>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <div class="bg-white p-6 rounded shadow-md">
            <h3 class="font-semibold text-lg">Total Pembayaran Lunas</h3>
            <p>{{ $totalLunas }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md">
            <h3 class="font-semibold text-lg">Total Pembayaran Belum Lunas</h3>
            <p>{{ $totalBelumLunas }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md">
            <h3 class="font-semibold text-lg">Total Siswa</h3>
            <p>{{ $totalSiswa }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md">
            <h3 class="font-semibold text-lg">Total Transaksi</h3>
            <p>{{ $totalTransaksi }}</p>
        </div>
    </div>
    
    <div class="bg-white p-6 mt-6 rounded shadow-md">
        <h3 class="font-semibold text-lg">Pengingat Jatuh Tempo Pembayaran</h3>
        <p>{{ $transaksiJatuhTempo }} transaksi yang akan jatuh tempo dalam 3 hari.</p>
    </div>
@endsection

@section('scripts')
    <script>
        // Ambil data dari controller
        const months = @json($months);
        const totals = @json($totals);

        // Membuat grafik menggunakan Chart.js
        const ctx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months.map(month => `Bulan ${month}`),
                datasets: [{
                    label: 'Total Pembayaran',
                    data: totals,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000000,
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
@endsection
