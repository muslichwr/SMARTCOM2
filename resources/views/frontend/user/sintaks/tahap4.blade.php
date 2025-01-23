@extends('layouts.appF')

@section('title', 'Tahap 4: Jadwal Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 4: Jadwal Proyek</h3>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Daftar Sintaks
                    </a>
                </div>
                <br>
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

                    <!-- Cek apakah user sudah validasi atau belum -->
                    @if ($sintaks && $sintaks->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $sintaks->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @endif

                    <!-- Tampilkan file jadwal proyek jika sudah diunggah -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Jadwal Proyek</h4>
                        @if ($sintaks && $sintaks->file_jadwal)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <a href="{{ asset('storage/' . $sintaks->file_jadwal) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                                        <x-heroicon-o-document-text class="w-5 h-5 mr-2" />
                                        Buka Jadwal Proyek
                                    </a>
                                    <span class="text-sm text-gray-600">Ukuran: {{ round(filesize(storage_path('app/public/' . $sintaks->file_jadwal)) / 1024, 2) }} KB</span>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada file jadwal proyek yang diunggah.</p>
                        @endif
                    </div>

                    <!-- Form Upload Jadwal Proyek -->
                    @if (!$sintaks || $sintaks->status_validasi != 'valid')
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap4') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="file_jadwal" class="block text-sm font-semibold text-gray-700">Upload Jadwal Proyek</label>
                                <input type="file" name="file_jadwal" id="file_jadwal" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2" accept=".pdf,.docx,.xlsx" required>
                                <p class="text-sm text-gray-500 mt-1">Format file yang diizinkan: PDF, DOCX, XLSX (Maksimal 10MB).</p>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    Upload Jadwal
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection