@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Statistik Pembayaran dan Grafik -->
    <h2 class="text-2xl font-bold mb-4">Statistik Pembayaran</h2>
    
    <div class="bg-white p-6 rounded shadow-md mb-6">
        <canvas id="lineChart"></canvas>
    </div>

    <div class="bg-white p-6 rounded shadow-md mb-6">
        <h3 class="font-semibold text-lg mb-2">Grafik Pembayaran Per Bulan (Real-Time)</h3>
        <canvas id="barChart"></canvas>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-6">
        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Pembayaran Lunas</h3>
            <p class="text-xl font-bold">{{ number_format($totalLunas, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Pembayaran Belum Lunas</h3>
            <p class="text-xl font-bold">{{ number_format($totalBelumLunas, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Siswa</h3>
            <p class="text-xl font-bold">{{ $totalSiswa }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow-md text-center">
            <h3 class="font-semibold text-lg mb-2">Total Transaksi</h3>
            <p class="text-xl font-bold">{{ $totalTransaksi }}</p>
        </div>
    </div>
    
    <div class="bg-white p-6 mt-6 rounded shadow-md">
        <h3 class="font-semibold text-lg">Pengingat Jatuh Tempo Pembayaran</h3>
        <p>{{ $transaksiJatuhTempo }} transaksi yang akan jatuh tempo dalam 3 hari.</p>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data untuk Line Chart dari controller
        const months = @json($months);
        const totals = @json($totals);

        const ctxLine = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctxLine, {
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
                    tension: 0.3 // buat garis smooth
                }],
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1000000,
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Bar Chart (real-time) - awal data kosong
        const ctxBar = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafik Real-Time Pembayaran per Bulan'
                    },
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Fungsi ambil data bar chart dari API Laravel
        function fetchBarChartData() {
            fetch('/api/chart-data')
                .then(response => response.json())
                .then(data => {
                    barChart.data.labels = data.labels;
                    barChart.data.datasets[0].data = data.values;
                    barChart.update();
                })
                .catch(error => {
                    console.error('Gagal memuat data chart:', error);
                });
        }

        // Panggil pertama kali dan refresh tiap 10 detik
        fetchBarChartData();
        setInterval(fetchBarChartData, 10000);
    </script>
@endsection
