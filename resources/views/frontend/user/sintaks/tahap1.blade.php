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

                    <!-- Cek apakah user sudah validasi atau belum -->
                    @if ($sintaks && $sintaks->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $sintaks->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Menampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="orientasi_masalah" class="block text-sm font-semibold text-gray-700">Orientasi Masalah</label>
                                <textarea name="orientasi_masalah" id="orientasi_masalah" rows="4" class="border border-gray-300 p-2 w-full bg-gray-100 rounded-lg" disabled>{{ $sintaks->orientasi_masalah ?? '' }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="rumusan_masalah" class="block text-sm font-semibold text-gray-700">Rumusan Masalah</label>
                                <textarea name="rumusan_masalah" id="rumusan_masalah" rows="3" class="border border-gray-300 p-2 w-full bg-gray-100 rounded-lg" disabled>{{ $sintaks->rumusan_masalah ?? '' }}</textarea>
                            </div>

                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form Input Tahap 1 -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap1') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="orientasi_masalah" class="block text-sm font-semibold text-gray-700">Orientasi Masalah</label>
                                    <textarea name="orientasi_masalah" id="orientasi_masalah" rows="4" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" placeholder="Masukkan orientasi masalah..." required>{{ $sintaks->orientasi_masalah ?? '' }}</textarea>
                                    @error('orientasi_masalah')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rumusan_masalah" class="block text-sm font-semibold text-gray-700">Rumusan Masalah</label>
                                    <textarea name="rumusan_masalah" id="rumusan_masalah" rows="3" class="border border-gray-300 p-2 w-full rounded-lg shadow-sm" placeholder="Masukkan rumusan masalah..." required>{{ $sintaks->rumusan_masalah ?? '' }}</textarea>
                                    @error('rumusan_masalah')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection