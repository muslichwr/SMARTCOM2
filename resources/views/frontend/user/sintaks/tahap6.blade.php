@extends('layouts.appF')

@section('title', 'Tahap 6: Pengumpulan Proyek')

@section('content')
    <br>
    <br>
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 6: Pengumpulan Proyek</h3>
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

                    <!-- Tampilkan file proyek dan laporan jika sudah diunggah -->
                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">File Proyek</h4>
                            @if ($sintaks && $sintaks->file_proyek)
                                <div class="flex items-center justify-between">
                                    <a href="{{ asset('storage/' . $sintaks->file_proyek) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                                        <x-heroicon-o-document-text class="w-5 h-5 mr-2" />
                                        Buka File Proyek
                                    </a>
                                    <span class="text-sm text-gray-600">Ukuran: {{ round(filesize(storage_path('app/public/' . $sintaks->file_proyek)) / 1024, 2) }} KB</span>
                                </div>
                            @else
                                <p class="text-gray-500">Tidak ada file proyek yang diunggah.</p>
                            @endif
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">File Laporan</h4>
                            @if ($sintaks && $sintaks->file_laporan)
                                <div class="flex items-center justify-between">
                                    <a href="{{ asset('storage/' . $sintaks->file_laporan) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded inline-flex items-center">
                                        <x-heroicon-o-document-text class="w-5 h-5 mr-2" />
                                        Buka File Laporan
                                    </a>
                                    <span class="text-sm text-gray-600">Ukuran: {{ round(filesize(storage_path('app/public/' . $sintaks->file_laporan)) / 1024, 2) }} KB</span>
                                </div>
                            @else
                                <p class="text-gray-500">Tidak ada file laporan yang diunggah.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Form Upload File Proyek dan Laporan -->
                    @if (!$sintaks || $sintaks->status_validasi != 'valid')
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap6') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="file_proyek" class="block text-sm font-semibold text-gray-700">Upload File Proyek</label>
                                    <input type="file" name="file_proyek" id="file_proyek" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2" accept=".pdf,.docx,.xlsx">
                                    <p class="text-sm text-gray-500 mt-1">Format file yang diizinkan: PDF, DOCX, XLSX (Maksimal 10MB).</p>
                                </div>

                                <div>
                                    <label for="file_laporan" class="block text-sm font-semibold text-gray-700">Upload File Laporan</label>
                                    <input type="file" name="file_laporan" id="file_laporan" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2" accept=".pdf,.docx,.xlsx">
                                    <p class="text-sm text-gray-500 mt-1">Format file yang diizinkan: PDF, DOCX, XLSX (Maksimal 10MB).</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    Upload File
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection