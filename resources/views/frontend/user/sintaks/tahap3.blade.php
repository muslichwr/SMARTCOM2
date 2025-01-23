@extends('layouts.appF')

@section('title', 'Form Tahap 3: Deskripsi Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 3: Deskripsi Proyek</h3>
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
                    @if ($sintaks && $sintaks->status_validasi == 'valid')
                        <div class="bg-green-100 p-4 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="text-green-600">Valid</span>
                            <p><strong>Feedback Guru:</strong> {{ $sintaks->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Menampilkan tugas anggota setelah validasi -->
                        <div class="space-y-4">
                            <div class="mb-4">
                                <label for="deskripsi_proyek" class="block text-sm font-semibold">Deskripsi Proyek</label>
                                <textarea name="deskripsi_proyek" id="deskripsi_proyek" rows="4" class="border border-gray-300 p-2 w-full bg-gray-100" disabled>{{ $sintaks->deskripsi_proyek ?? '' }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="tugas_anggota" class="block text-sm font-semibold">Tugas Anggota</label>
                                <ul class="list-disc pl-6">
                                    @foreach (json_decode($sintaks->tugas_anggota) as $key => $tugas)
                                        <li><strong>{{ $anggotaKelompok[$key]->user->name }}:</strong> {{ $tugas }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @else
                        <!-- Form Input Tahap 3 -->
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap3') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="deskripsi_proyek" class="block text-sm font-semibold">Deskripsi Proyek</label>
                                <textarea name="deskripsi_proyek" id="deskripsi_proyek" rows="4" class="border border-gray-300 p-2 w-full" placeholder="Masukkan deskripsi proyek..." required>{{ $sintaks->deskripsi_proyek ?? '' }}</textarea>
                                @error('deskripsi_proyek')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="tugas_anggota" class="block text-sm font-semibold">Tugas Anggota</label>
                                <div class="space-y-2" id="tugas-anggota-container">
                                    @foreach ($anggotaKelompok as $anggota)
                                        <div class="flex items-center gap-2">
                                            <label class="text-sm">{{ $anggota->user->name }}</label>
                                            <input type="text" name="tugas_anggota[{{ $loop->index }}]" value="{{ json_decode($sintaks->tugas_anggota)[$loop->index] ?? '' }}" class="border border-gray-300 p-2 w-full" placeholder="Masukkan tugas {{ $anggota->user->name }}..." required />
                                        </div>
                                    @endforeach
                                </div>
                                @error('tugas_anggota')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
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