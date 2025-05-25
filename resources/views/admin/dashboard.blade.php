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
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Aktivitas Terbaru</h2>
            
            <!-- Filter Toggle Button -->
            <div class="flex space-x-2">
                <button id="filterToggle" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm flex items-center hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <button id="exportBtn" class="bg-green-500 text-white px-3 py-1 rounded-md text-sm flex items-center hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
        
        <!-- Filter Form (Hidden by default) -->
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
        
        <!-- Search Box -->
        <div class="mb-4 relative">
            <input type="text" id="searchInput" placeholder="Cari user, materi, atau bab..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm pl-9">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
        </div>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table id="activitiesTable" class="min-w-full bg-white">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="py-2 px-3 border-b cursor-pointer hover:bg-gray-100" onclick="sortTable(0)">
                            <div class="flex items-center">
                                <span>User</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="py-2 px-3 border-b cursor-pointer hover:bg-gray-100" onclick="sortTable(1)">
                            <div class="flex items-center">
                                <span>Materi</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="py-2 px-3 border-b cursor-pointer hover:bg-gray-100" onclick="sortTable(2)">
                            <div class="flex items-center">
                                <span>Bab</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="py-2 px-3 border-b cursor-pointer hover:bg-gray-100" onclick="sortTable(3)">
                            <div class="flex items-center">
                                <span>Status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                        <th class="py-2 px-3 border-b cursor-pointer hover:bg-gray-100" onclick="sortTable(4)">
                            <div class="flex items-center">
                                <span>Tanggal</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($recentActivities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-3 border-b">{{ $activity->user->name ?? 'User tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b">{{ $activity->bab->materi->judul ?? 'Materi tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b">{{ $activity->bab->judul ?? 'Bab tidak ditemukan' }}</td>
                            <td class="py-2 px-3 border-b">
                                @if($activity->status == 1)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                                @else
                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs">Belum</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 border-b">{{ $activity->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">Belum ada aktivitas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination & Info -->
        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
            <div class="text-sm text-gray-600 mb-2 sm:mb-0">
                Menampilkan {{ $recentActivities->firstItem() ?? 0 }} sampai {{ $recentActivities->lastItem() ?? 0 }} dari {{ $recentActivities->total() ?? 0 }} data
            </div>
            <div>
                {{ $recentActivities->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Export -->
<div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Export Data</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <p class="text-sm text-gray-600 mb-4">Pilih format untuk mengunduh data aktivitas:</p>
        <div class="flex justify-center space-x-3">
            <button id="exportCSV" class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                Export CSV
            </button>
            <button id="exportExcel" class="bg-green-500 text-white px-4 py-2 rounded-md text-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                Export Excel
            </button>
            <button id="exportPDF" class="bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                Export PDF
            </button>
        </div>
    </div>
</div>

<!-- JavaScript untuk toggle filter dan pencarian tabel -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter toggle
        const filterToggle = document.getElementById('filterToggle');
        const filterForm = document.getElementById('filterForm');
        
        // Tampilkan form jika ada filter yang aktif
        @if(request()->has('status') || request()->has('start_date') || request()->has('end_date'))
            filterForm.classList.remove('hidden');
        @endif
        
        filterToggle.addEventListener('click', function() {
            filterForm.classList.toggle('hidden');
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('activitiesTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                
                // Skip empty state row
                if (cells.length === 1 && cells[0].getAttribute('colspan')) {
                    continue;
                }
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    if (cellText.indexOf(searchTerm) > -1) {
                        found = true;
                        break;
                    }
                }
                
                if (found) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        
        // Export modal functionality
        const exportBtn = document.getElementById('exportBtn');
        const exportModal = document.getElementById('exportModal');
        const closeModal = document.getElementById('closeModal');
        
        exportBtn.addEventListener('click', function() {
            exportModal.classList.remove('hidden');
        });
        
        closeModal.addEventListener('click', function() {
            exportModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === exportModal) {
                exportModal.classList.add('hidden');
            }
        });
        
        // Export buttons functionality
        document.getElementById('exportCSV').addEventListener('click', function() {
            // Add CSV export logic here
            alert('Export CSV akan diimplementasikan');
            exportModal.classList.add('hidden');
        });
        
        document.getElementById('exportExcel').addEventListener('click', function() {
            // Add Excel export logic here
            alert('Export Excel akan diimplementasikan');
            exportModal.classList.add('hidden');
        });
        
        document.getElementById('exportPDF').addEventListener('click', function() {
            // Add PDF export logic here
            alert('Export PDF akan diimplementasikan');
            exportModal.classList.add('hidden');
        });
    });
    
    // Table sorting
    function sortTable(colIndex) {
        const table = document.getElementById('activitiesTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        
        // Skip if there's only the empty state row
        if (rows.length === 1 && rows[0].getElementsByTagName('td').length === 1 && 
            rows[0].getElementsByTagName('td')[0].getAttribute('colspan')) {
            return;
        }
        
        // Get current sort direction
        const th = table.getElementsByTagName('th')[colIndex];
        const sortDir = th.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
        
        // Update all headers to show no sort direction
        const headers = table.getElementsByTagName('th');
        for (let i = 0; i < headers.length; i++) {
            headers[i].setAttribute('data-sort-dir', '');
        }
        
        // Set new sort direction for clicked header
        th.setAttribute('data-sort-dir', sortDir);
        
        // Sort the rows
        rows.sort((a, b) => {
            const cellA = a.getElementsByTagName('td')[colIndex].textContent.trim();
            const cellB = b.getElementsByTagName('td')[colIndex].textContent.trim();
            
            // Special case for status column
            if (colIndex === 3) {
                const statusA = cellA.includes('Selesai') ? 1 : 0;
                const statusB = cellB.includes('Selesai') ? 1 : 0;
                return sortDir === 'asc' ? statusA - statusB : statusB - statusA;
            }
            
            // Special case for date column
            if (colIndex === 4) {
                const dateA = new Date(cellA.replace(/(\d+)\s+(\w+)\s+(\d+)/, '$3-$2-$1'));
                const dateB = new Date(cellB.replace(/(\d+)\s+(\w+)\s+(\d+)/, '$3-$2-$1'));
                return sortDir === 'asc' ? dateA - dateB : dateB - dateA;
            }
            
            // Default string comparison
            if (sortDir === 'asc') {
                return cellA.localeCompare(cellB);
            } else {
                return cellB.localeCompare(cellA);
            }
        });
        
        // Reappend rows in new order
        rows.forEach(row => tbody.appendChild(row));
    }
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
@endsection