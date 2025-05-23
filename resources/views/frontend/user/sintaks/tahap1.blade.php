@extends('layouts.appF')

@section('title', 'Tahap 1: Orientasi Masalah')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 1: Orientasi Masalah</h3>
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

                    <!-- Orientasi Masalah (Read Only) - Diisi oleh guru -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-3">Orientasi Masalah (Diisi oleh Guru)</h4>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700">Orientasi Masalah</label>
                            <div class="border border-gray-300 p-3 rounded-lg bg-white">
                                {!! nl2br(e($tahapSatu->orientasi_masalah ?? 'Belum ada orientasi masalah. Silakan tunggu guru mengisi orientasi masalah.')) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Cek status validasi tahap satu -->
                    @if ($tahapSatu && $tahapSatu->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapSatu->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Menampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="rumusan_masalah" class="block text-sm font-semibold text-gray-700">Rumusan Masalah</label>
                                <textarea name="rumusan_masalah" id="rumusan_masalah" rows="3" class="border border-gray-300 p-2 w-full bg-gray-100 rounded-lg" disabled>{{ $tahapSatu->rumusan_masalah ?? '' }}</textarea>
                            </div>

                            @if($tahapSatu->file_indikator_masalah)
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700">File Indikator Masalah</label>
                                <a href="{{ asset('storage/' . $tahapSatu->file_indikator_masalah) }}" target="_blank" class="text-blue-500 hover:underline">
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Lihat File Indikator Masalah
                                    </div>
                                </a>
                            </div>
                            @endif

                            @if($tahapSatu->file_hasil_analisis)
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700">File Hasil Analisis</label>
                                <a href="{{ asset('storage/' . $tahapSatu->file_hasil_analisis) }}" target="_blank" class="text-blue-500 hover:underline">
                                    <div class="flex items-center mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Lihat File Hasil Analisis
                                    </div>
                                </a>
                            </div>
                            @endif

                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form Input Tahap 1 (Menggabungkan semua kondisi yang tidak valid ke dalam satu form) -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap1') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="rumusan_masalah" class="block text-sm font-semibold text-gray-700">Rumusan Masalah</label>
                                    <textarea name="rumusan_masalah" id="rumusan_masalah" rows="3" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" placeholder="Masukkan rumusan masalah berdasarkan orientasi masalah di atas..." required>{{ $tahapSatu->rumusan_masalah ?? '' }}</textarea>
                                    @error('rumusan_masalah')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="file_indikator_masalah" class="block text-sm font-semibold text-gray-700">File Indikator Masalah (PDF, DOC, DOCX, maks. 10MB)</label>
                                    <input type="file" name="file_indikator_masalah" id="file_indikator_masalah" class="mt-1 block w-full border border-gray-300 p-2 rounded-lg shadow-sm">
                                    @error('file_indikator_masalah')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($tahapSatu && $tahapSatu->file_indikator_masalah)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $tahapSatu->file_indikator_masalah) }}" target="_blank" class="text-blue-500 hover:underline">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                File indikator masalah saat ini (klik untuk melihat)
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <label for="file_hasil_analisis" class="block text-sm font-semibold text-gray-700">File Hasil Analisis (PDF, DOC, DOCX, maks. 10MB)</label>
                                    <input type="file" name="file_hasil_analisis" id="file_hasil_analisis" class="mt-1 block w-full border border-gray-300 p-2 rounded-lg shadow-sm">
                                    @error('file_hasil_analisis')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    @if($tahapSatu && $tahapSatu->file_hasil_analisis)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $tahapSatu->file_hasil_analisis) }}" target="_blank" class="text-blue-500 hover:underline">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                File hasil analisis saat ini (klik untuk melihat)
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tampilkan status validasi jika ada -->
                            @if ($tahapSatu && $tahapSatu->status_validasi == 'invalid')
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg my-4">
                                <strong>Status Validasi:</strong> <span class="font-semibold">Ditolak</span>
                                <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapSatu->feedback_guru ?? 'Tidak ada feedback dari guru' }}</p>
                            </div>
                            @elseif ($tahapSatu && $tahapSatu->status_validasi == 'pending')
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg my-4">
                                <strong>Status Validasi:</strong> <span class="font-semibold">Menunggu Validasi</span>
                                <p>Tahap 1 sudah disimpan dan menunggu validasi dari guru.</p>
                            </div>
                            @endif

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    {{ $tahapSatu ? 'Update Data' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection