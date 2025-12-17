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

    <!-- Tabel Data Terbaru with DataTables -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Aktivitas Terbaru</h2>
            
            <!-- Filter Toggle Button -->
            <button id="filterToggle" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm flex items-center hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter Server
            </button>
        </div>
        
        <!-- Filter Form (Hidden by default) - Server-side filter -->
        <div id="filterForm" class="mb-6 bg-gray-50 p-4 rounded-lg hidden">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Selesai</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Selesai</option>
                    </select>
                </div>
                
                <div class="flex space-x-2">
                    <div class="w-1/2">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm">
                    </div>
                    
                    <div class="w-1/2">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm">
                    </div>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <i class="fas fa-filter mr-1"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white py-1 px-3 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <!-- DataTables handles search, sort, pagination, and export automatically -->
        <div class="overflow-x-auto">
            <table id="activitiesTable" class="min-w-full bg-white display" style="width:100%">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="py-2 px-3 border-b">User</th>
                        <th class="py-2 px-3 border-b">Materi</th>
                        <th class="py-2 px-3 border-b">Bab</th>
                        <th class="py-2 px-3 border-b">Status</th>
                        <th class="py-2 px-3 border-b">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($recentActivities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border-b">{{ $activity->user->name ?? 'User tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b">{{ $activity->bab->materi->judul ?? 'Materi tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b">{{ $activity->bab->judul ?? 'Bab tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b" data-order="{{ $activity->status }}">
                                @if($activity->status == 1)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                                @else
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Belum</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 border-b" data-order="{{ $activity->updated_at->timestamp }}">{{ $activity->updated_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        {{-- DataTables will handle empty state --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables Core + Extensions -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTables with export buttons
        $('#activitiesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '📋 Copy',
                    className: 'bg-gray-500 text-white px-3 py-1 rounded text-sm hover:bg-gray-600'
                },
                {
                    extend: 'csv',
                    text: '📄 CSV',
                    className: 'bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600',
                    title: 'Aktivitas_Dashboard_{{ date("Y-m-d") }}'
                },
                {
                    extend: 'excel',
                    text: '📊 Excel',
                    className: 'bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600',
                    title: 'Aktivitas_Dashboard_{{ date("Y-m-d") }}'
                },
                {
                    extend: 'pdf',
                    text: '📕 PDF',
                    className: 'bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600',
                    title: 'Aktivitas Dashboard',
                    customize: function(doc) {
                        doc.styles.tableHeader = {
                            bold: true,
                            fontSize: 11,
                            color: 'white',
                            fillColor: '#3B82F6',
                            alignment: 'left'
                        };
                    }
                },
                {
                    extend: 'print',
                    text: '🖨️ Print',
                    className: 'bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600',
                    title: 'Aktivitas Dashboard'
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                emptyTable: "Belum ada aktivitas",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                }
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            order: [[4, 'desc']], // Sort by date descending
            responsive: true
        });
        
        // Filter toggle functionality
        $('#filterToggle').on('click', function() {
            $('#filterForm').toggleClass('hidden');
        });
        
        // Show filter if active
        @if(request()->has('status') || request()->has('start_date') || request()->has('end_date'))
            $('#filterForm').removeClass('hidden');
        @endif
    });
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari controller
    const months = @json($months);
    const completionData = @json($completionData);
    
    // Grafik Menggunakan Chart.js dengan data dinamis
    const ctx = document.getElementById('completionChart').getContext('2d');
    const completionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Penyelesaian',
                data: completionData,
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

<style>
    /* Custom styling for DataTables buttons */
    .dt-buttons {
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .dt-buttons .dt-button {
        border: none !important;
        padding: 0.5rem 1rem !important;
        border-radius: 0.375rem !important;
        font-size: 0.875rem !important;
        cursor: pointer;
        transition: all 0.2s;
    }
    .dt-buttons .dt-button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    /* DataTables search and length styling */
    .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        margin-left: 0.5rem;
    }
    .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
    }
    /* Pagination styling */
    .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem !important;
        margin: 0 0.125rem !important;
        border-radius: 0.25rem !important;
    }
    .dataTables_paginate .paginate_button.current {
        background: #3B82F6 !important;
        color: white !important;
        border: none !important;
    }
    .dataTables_paginate .paginate_button:hover {
        background: #E5E7EB !important;
        border: none !important;
    }
</style>
@endsection