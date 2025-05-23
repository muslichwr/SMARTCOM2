@extends('layouts.appF')

@section('title', 'Tahap 4: Pelaksanaan Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-4xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 4: Pelaksanaan Proyek</h3>
                        <p class="text-gray-600">Materi: {{ $materi->judul }} - Kelompok: {{ $kelompok->kelompok }}</p>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Daftar Sintaks
                    </a>
                </div>
                <div class="p-6">
                    <!-- Menampilkan pesan sukses atau error -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif

                    <!-- Cek apakah tahap sudah divalidasi atau belum -->
                    @if ($tahapEmpat && $tahapEmpat->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapEmpat->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @elseif ($tahapEmpat && $tahapEmpat->status_validasi == 'invalid')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Tidak Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapEmpat->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @else
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Menunggu Validasi</span>
                            <p class="mt-2">Silakan lengkapi tugas-tugas pelaksanaan proyek sesuai dengan jadwal yang telah ditentukan.</p>
                        </div>
                    @endif

                    <!-- Progress pelaksanaan proyek -->
                    @if($tahapEmpat && $tahapEmpat->tasks->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">Progress Pelaksanaan Proyek</h4>
                            @php
                                $totalTasks = $tahapEmpat->tasks->count();
                                $completedTasks = $tahapEmpat->tasks->where('status', 'selesai')->count();
                                $inProgressTasks = $tahapEmpat->tasks->where('status', 'proses')->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            
                            <div class="bg-gray-200 rounded-full h-4 mb-2">
                                <div class="bg-blue-500 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai ({{ $percentage }}%)</span>
                                <span>{{ $inProgressTasks }} tugas dalam proses</span>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Daftar Tugas Pelaksanaan Proyek</h4>
                        
                        @if($tahapEmpat && $tahapEmpat->tasks->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Judul Tugas</th>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Penanggungjawab</th>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Deskripsi</th>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Deadline</th>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Status</th>
                                            <th class="py-2 px-4 border-b text-left text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tahapEmpat->tasks as $task)
                                            <tr class="hover:bg-gray-50 {{ strtotime($task->deadline) < time() && $task->status != 'selesai' ? 'bg-red-50' : '' }}">
                                                <td class="py-2 px-4 border-b">{{ $task->judul_task }}</td>
                                                <td class="py-2 px-4 border-b">{{ $task->user->name }}</td>
                                                <td class="py-2 px-4 border-b">{{ $task->deskripsi_task }}</td>
                                                <td class="py-2 px-4 border-b">
                                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y') }}
                                                    @if(strtotime($task->deadline) < time() && $task->status != 'selesai')
                                                        <span class="text-red-500 text-xs ml-1">(Terlambat)</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b">
                                                    @if($task->status == 'belum_mulai')
                                                        <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Belum Mulai</span>
                                                    @elseif($task->status == 'proses')
                                                        <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">Dalam Proses</span>
                                                    @elseif($task->status == 'selesai')
                                                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Selesai</span>
                                                    @endif
                                                </td>
                                                <td class="py-2 px-4 border-b">
                                                    @if($task->user_id == auth()->id())
                                                        <button onclick="openUpdateTaskModal('{{ $task->id }}', '{{ $task->status }}', '{{ $task->catatan }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                            Update
                                                        </button>
                                                    @endif
                                                    <button onclick="openDetailTaskModal('{{ $task->id }}', '{{ $task->judul_task }}', '{{ $task->deskripsi_task }}', '{{ $task->user->name }}', '{{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y') }}', '{{ $task->status }}', '{{ $task->catatan }}', '{{ $task->file_tugas }}')" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm ml-1">
                                                        Detail
                                                    </button>
                                                    @if($task->file_tugas)
                                                        <a href="{{ asset('storage/' . $task->file_tugas) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm ml-1">
                                                            <i class="fas fa-download mr-1"></i> File
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada tugas pelaksanaan proyek yang ditambahkan.</p>
                        @endif
                    </div>

                    <!-- Tambah Tugas Baru (jika status validasi belum valid) -->
                    @if(!$tahapEmpat || $tahapEmpat->status_validasi != 'valid')
                        <div class="mt-6">
                            <button onclick="openAddTaskModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Tugas Baru
                            </button>
                        </div>
                    @endif

                    <!-- Modal untuk Tambah Tugas Baru -->
                    <div id="addTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                            <h3 class="text-lg font-semibold mb-4">Tambah Tugas Baru</h3>
                            <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap4/tambah-tugas') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="judul_task" class="block text-sm font-semibold text-gray-700">Judul Tugas</label>
                                    <input type="text" name="judul_task" id="judul_task" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label for="deskripsi_task" class="block text-sm font-semibold text-gray-700">Deskripsi Tugas</label>
                                    <textarea name="deskripsi_task" id="deskripsi_task" rows="3" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" required></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="user_id" class="block text-sm font-semibold text-gray-700">Penanggungjawab</label>
                                    <select name="user_id" id="user_id" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" required>
                                        @foreach($kelompok->anggotas as $anggota)
                                            <option value="{{ $anggota->user_id }}">{{ $anggota->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="deadline" class="block text-sm font-semibold text-gray-700">Deadline</label>
                                    <input type="date" name="deadline" id="deadline" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" required min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-4">
                                    <label for="file_tugas" class="block text-sm font-semibold text-gray-700">Upload File Tugas (opsional)</label>
                                    <input type="file" name="file_tugas" id="file_tugas" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm">
                                    <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (maks. 10MB)</p>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeAddTaskModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal untuk Update Status Tugas -->
                    <div id="updateTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                            <h3 class="text-lg font-semibold mb-4">Update Status Tugas</h3>
                            <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap4') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="task_id" id="update_task_id">
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                                    <select name="status" id="update_status" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" required>
                                        <option value="belum_mulai">Belum Mulai</option>
                                        <option value="proses">Dalam Proses</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="catatan" class="block text-sm font-semibold text-gray-700">Catatan Progress</label>
                                    <textarea name="catatan" id="update_catatan" rows="3" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="file_tugas" class="block text-sm font-semibold text-gray-700">Upload File Tugas (opsional)</label>
                                    <input type="file" name="file_tugas" id="file_tugas" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm">
                                    <p class="text-sm text-gray-500 mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (maks. 10MB)</p>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="closeUpdateTaskModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                        Batal
                                    </button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Modal untuk Detail Tugas -->
                    <div id="detailTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                        <div class="bg-white rounded-lg p-6 w-full max-w-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold" id="detail_judul"></h3>
                                <button onclick="closeDetailTaskModal()" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="mb-4">
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <div class="mb-3">
                                        <span class="text-sm text-gray-500">Penanggungjawab:</span>
                                        <p class="font-medium" id="detail_penanggungjawab"></p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-sm text-gray-500">Deadline:</span>
                                        <p class="font-medium" id="detail_deadline"></p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-sm text-gray-500">Status:</span>
                                        <p class="font-medium" id="detail_status"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <span class="text-sm text-gray-500">Deskripsi Tugas:</span>
                                <p class="mt-1" id="detail_deskripsi"></p>
                            </div>
                            
                            <div class="mb-4" id="detail_catatan_container">
                                <span class="text-sm text-gray-500">Catatan Progress:</span>
                                <p class="mt-1" id="detail_catatan"></p>
                            </div>
                            
                            <div id="detail_file_container" class="mb-4 hidden">
                                <span class="text-sm text-gray-500">File Tugas:</span>
                                <div class="mt-2">
                                    <a id="detail_file_link" href="#" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download File
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex justify-end">
                                <button onclick="closeDetailTaskModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAddTaskModal() {
            document.getElementById('addTaskModal').classList.remove('hidden');
        }
        
        function closeAddTaskModal() {
            document.getElementById('addTaskModal').classList.add('hidden');
        }
        
        function openUpdateTaskModal(taskId, status, catatan) {
            document.getElementById('update_task_id').value = taskId;
            document.getElementById('update_status').value = status;
            document.getElementById('update_catatan').value = catatan || '';
            document.getElementById('updateTaskModal').classList.remove('hidden');
        }
        
        function closeUpdateTaskModal() {
            document.getElementById('updateTaskModal').classList.add('hidden');
        }
        
        function openDetailTaskModal(id, judul, deskripsi, penanggungjawab, deadline, status, catatan, fileUrl) {
            document.getElementById('detail_judul').textContent = judul;
            document.getElementById('detail_deskripsi').textContent = deskripsi;
            document.getElementById('detail_penanggungjawab').textContent = penanggungjawab;
            document.getElementById('detail_deadline').textContent = deadline;
            
            // Format status
            let statusText = '';
            if (status === 'belum_mulai') {
                statusText = 'Belum Mulai';
                document.getElementById('detail_status').innerHTML = '<span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Belum Mulai</span>';
            } else if (status === 'proses') {
                statusText = 'Dalam Proses';
                document.getElementById('detail_status').innerHTML = '<span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">Dalam Proses</span>';
            } else if (status === 'selesai') {
                statusText = 'Selesai';
                document.getElementById('detail_status').innerHTML = '<span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Selesai</span>';
            }
            
            // Catatan
            if (catatan && catatan !== 'null' && catatan !== '') {
                document.getElementById('detail_catatan').textContent = catatan;
                document.getElementById('detail_catatan_container').classList.remove('hidden');
            } else {
                document.getElementById('detail_catatan_container').classList.add('hidden');
            }
            
            // File
            if (fileUrl && fileUrl !== 'null' && fileUrl !== '') {
                document.getElementById('detail_file_link').href = "{{ asset('storage') }}/" + fileUrl;
                document.getElementById('detail_file_container').classList.remove('hidden');
            } else {
                document.getElementById('detail_file_container').classList.add('hidden');
            }
            
            document.getElementById('detailTaskModal').classList.remove('hidden');
        }
        
        function closeDetailTaskModal() {
            document.getElementById('detailTaskModal').classList.add('hidden');
        }
    </script>
@endsection