@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-600">Halaman Statistik dan informasi penting.</p>
    </div>

    <!-- Pesan Session -->
    @if (session('message'))
        <div class="bg-blue-100 p-4 rounded-lg text-blue-800 mb-8">
            {{ session('message') }}
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card: Jumlah Materi -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Materi</h2>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $jumlahMateri }}</p>
            <p class="text-sm text-gray-500 mt-1">Total materi yang tersedia</p>
        </div>

        <!-- Card: Jumlah Kelompok -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700">Jumlah Kelompok</h2>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $jumlahKelompok }}</p>
            <p class="text-sm text-gray-500 mt-1">Total kelompok yang dibuat</p>
        </div>

        <!-- Card: User Menyelesaikan -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700">User Menyelesaikan</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $userCompleted }}</p>
            <p class="text-sm text-gray-500 mt-1">Total user yang selesai</p>
        </div>

        <!-- Card: Persentase Penyelesaian -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700">Persentase Penyelesaian</h2>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $completionPercentage }}%</p>
            <p class="text-sm text-gray-500 mt-1">Dari {{ $totalUsersAttempted }} user</p>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Grafik Penyelesaian Materi</h2>
        <canvas id="completionChart" class="w-full h-64"></canvas>
    </div>

    <!-- Tabel Data Terbaru -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">User</th>
                        <th class="py-2 px-4 border-b">Materi</th>
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh Data -->
                    <tr>
                        <td class="py-2 px-4 border-b">User 1</td>
                        <td class="py-2 px-4 border-b">Materi 1</td>
                        <td class="py-2 px-4 border-b">Selesai</td>
                        <td class="py-2 px-4 border-b">2023-10-01</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b">User 2</td>
                        <td class="py-2 px-4 border-b">Materi 2</td>
                        <td class="py-2 px-4 border-b">Belum Selesai</td>
                        <td class="py-2 px-4 border-b">2023-10-02</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Sertakan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Contoh Grafik Menggunakan Chart.js
    const ctx = document.getElementById('completionChart').getContext('2d');
    const completionChart = new Chart(ctx, {
        type: 'bar', // Tipe grafik (line, bar, pie, dll.)
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'], // Label bulan
            datasets: [{
                label: 'Jumlah Penyelesaian',
                data: [12, 19, 3, 5, 2, 3], // Data contoh
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection