@extends('layouts.appF')

@section('title', 'Tahap 2: Rancangan Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 2: Rancangan Proyek</h3>
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

                    <!-- Cek status validasi tahap dua -->
                    @if ($tahapDua && $tahapDua->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapDua->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Menampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="deskripsi_rancangan" class="block text-sm font-semibold text-gray-700">Deskripsi Rancangan</label>
                                <textarea name="deskripsi_rancangan" id="deskripsi_rancangan" rows="4" class="border border-gray-300 p-2 w-full bg-gray-100 rounded-lg" disabled>{{ $tahapDua->deskripsi_rancangan ?? '' }}</textarea>
                            </div>

                            @if($tahapDua->file_rancangan)
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700">File Rancangan</label>
                                <a href="{{ asset('storage/' . $tahapDua->file_rancangan) }}" target="_blank" class="text-blue-500 hover:underline">
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Lihat File Rancangan
                                    </div>
                                </a>
                            </div>
                            @endif

                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form Input Tahap 2 -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap2') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="deskripsi_rancangan" class="block text-sm font-semibold text-gray-700">Deskripsi Rancangan</label>
                                    <textarea name="deskripsi_rancangan" id="deskripsi_rancangan" rows="4" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" placeholder="Masukkan deskripsi rancangan proyek..." required>{{ $tahapDua->deskripsi_rancangan ?? '' }}</textarea>
                                    @error('deskripsi_rancangan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="file_rancangan" class="block text-sm font-semibold text-gray-700">File Rancangan (PDF, DOC, DOCX, XLS, XLSX, maks. 10MB)</label>
                                    <input type="file" name="file_rancangan" id="file_rancangan" class="mt-1 block w-full border border-gray-300 p-2 rounded-lg shadow-sm">
                                    @error('file_rancangan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($tahapDua && $tahapDua->file_rancangan)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $tahapDua->file_rancangan) }}" target="_blank" class="text-blue-500 hover:underline">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                File rancangan saat ini (klik untuk melihat)
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tampilkan status validasi jika ada -->
                            @if ($tahapDua && $tahapDua->status_validasi == 'invalid')
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg my-4">
                                <strong>Status Validasi:</strong> <span class="font-semibold">Ditolak</span>
                                <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapDua->feedback_guru ?? 'Tidak ada feedback dari guru' }}</p>
                            </div>
                            @elseif ($tahapDua && $tahapDua->status_validasi == 'pending')
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg my-4">
                                <strong>Status Validasi:</strong> <span class="font-semibold">Menunggu Validasi</span>
                                <p>Tahap 2 sudah disimpan dan menunggu validasi dari guru.</p>
                            </div>
                            @endif

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    {{ $tahapDua ? 'Update Data' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection