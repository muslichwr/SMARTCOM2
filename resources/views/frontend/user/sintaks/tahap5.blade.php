@extends('layouts.appF')

@section('title', 'Tahap 5: Pelaksanaan Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 5: Pelaksanaan Proyek</h3>
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
                        <div class="bg-green-200 p-4 rounded-lg mb-4">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-200 p-4 rounded-lg mb-4">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif

                    <!-- Cek apakah user sudah validasi atau belum -->
                    @if ($sintaksTahap5 && $sintaksTahap5->status_validasi == 'valid')
                        <div class="bg-green-100 p-4 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="text-green-600">Valid</span>
                            <p><strong>Feedback Guru:</strong> {{ $sintaksTahap5->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @endif

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold mb-2">Progress Proyek</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $totalTasks = count(json_decode($sintaksTahap5->to_do_list, true));
                                $completedTasks = collect(json_decode($sintaksTahap5->to_do_list, true))->where('status', 'completed')->count();
                                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                            @endphp
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai.</p>
                    </div>

                    <!-- Tampilkan To-Do List -->
                    @if ($sintaksTahap5 && $sintaksTahap5->status_validasi == 'valid')
                        <!-- Tampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            @foreach (json_decode($sintaksTahap5->to_do_list, true) as $task)
                                <div class="bg-white p-4 rounded-lg shadow-md">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold">{{ $task['tugas'] }}</p>
                                            <p class="text-xs text-gray-500">Oleh: {{ $task['anggota'] }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 rounded-full 
                                                @if($task['status'] == 'pending') bg-yellow-500
                                                @elseif($task['status'] == 'in progress') bg-blue-500
                                                @elseif($task['status'] == 'completed') bg-green-500
                                                @endif">
                                            </div>
                                            <span class="text-sm">
                                                @if($task['status'] == 'pending') Pending
                                                @elseif($task['status'] == 'in progress') In Progress
                                                @elseif($task['status'] == 'completed') Completed
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tombol Sudah Divalidasi -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form untuk menampilkan dan memperbarui To-Do List -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap5') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                @if (!empty($sintaksTahap5->to_do_list))
                                    @foreach (json_decode($sintaksTahap5->to_do_list, true) as $key => $task)
                                        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold">{{ $task['tugas'] }}</p>
                                                    <p class="text-xs text-gray-500">Oleh: {{ $task['anggota'] }}</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div class="relative">
                                                        <select name="to_do_list[{{ $key }}][status]" class="appearance-none border border-gray-300 p-2 text-sm rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                            <option value="pending" {{ $task['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="in progress" {{ $task['status'] == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="completed" {{ $task['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                                                        </select>
                                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="w-4 h-4 rounded-full 
                                                        @if($task['status'] == 'pending') bg-yellow-500
                                                        @elseif($task['status'] == 'in progress') bg-blue-500
                                                        @elseif($task['status'] == 'completed') bg-green-500
                                                        @endif">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500">Belum ada tugas yang ditambahkan.</p>
                                @endif
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection