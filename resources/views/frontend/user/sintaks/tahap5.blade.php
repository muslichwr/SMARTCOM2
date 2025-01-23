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

                    <!-- Form untuk menampilkan dan memperbarui To-Do List -->
                    <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap5') }}" method="POST">
                        @csrf
                        @foreach ($sintaks->to_do_list as $key => $task)
                            <div class="mb-4 flex items-center gap-2">
                                <label class="text-sm">{{ $task['tugas'] }} ({{ $task['anggota'] }})</label>
                                <select name="to_do_list[{{ $key }}][status]" class="border border-gray-300 p-2">
                                    <option value="pending" {{ $task['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in progress" {{ $task['status'] == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        @endforeach

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
