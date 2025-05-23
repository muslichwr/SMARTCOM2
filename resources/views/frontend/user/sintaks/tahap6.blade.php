@extends('layouts.appF')

@section('title', 'Tahap 6: Presentasi Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 6: Presentasi Proyek</h3>
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

                    <!-- Cek apakah tahap sudah divalidasi -->
                    @if ($tahapEnam && $tahapEnam->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapEnam->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @elseif ($tahapEnam && $tahapEnam->status_validasi == 'invalid')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Invalid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapEnam->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @endif

                    <!-- Form untuk input presentasi jika belum divalidasi -->
                    @if (!$tahapEnam || $tahapEnam->status_validasi != 'valid')
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap6') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div class="mb-4">
                                    <label for="link_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Link Presentasi/Meeting</label>
                                    <input type="url" name="link_presentasi" id="link_presentasi" 
                                        value="{{ $tahapEnam->link_presentasi ?? '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                        placeholder="https://meet.google.com/..." required>
                                    <p class="mt-1 text-xs text-gray-500">Masukkan link Google Meet, Zoom, atau platform presentasi online lainnya</p>
                                </div>

                                <div class="mb-4">
                                    <label for="jadwal_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Presentasi</label>
                                    <input type="datetime-local" name="jadwal_presentasi" id="jadwal_presentasi" 
                                        value="{{ $tahapEnam->jadwal_presentasi ? date('Y-m-d\TH:i', strtotime($tahapEnam->jadwal_presentasi)) : '' }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <p class="mt-1 text-xs text-gray-500">Tentukan tanggal dan waktu presentasi akan dilaksanakan</p>
                                </div>

                                <div class="mb-4">
                                    <label for="catatan_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Catatan Presentasi</label>
                                    <textarea name="catatan_presentasi" id="catatan_presentasi" rows="4" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                        placeholder="Masukkan catatan atau informasi tambahan mengenai presentasi...">{{ $tahapEnam->catatan_presentasi ?? '' }}</textarea>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                        Simpan Presentasi
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <!-- Tampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-lg font-semibold mb-2">Link Presentasi</h4>
                                <a href="{{ $tahapEnam->link_presentasi }}" target="_blank" class="text-blue-600 hover:underline break-words">
                                    {{ $tahapEnam->link_presentasi }}
                                </a>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-lg font-semibold mb-2">Jadwal Presentasi</h4>
                                <p class="text-gray-700">
                                    {{ \Carbon\Carbon::parse($tahapEnam->jadwal_presentasi)->format('d F Y, H:i') }}
                                </p>
                            </div>

                            @if($tahapEnam->catatan_presentasi)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-lg font-semibold mb-2">Catatan Presentasi</h4>
                                <p class="text-gray-700 whitespace-pre-line">{{ $tahapEnam->catatan_presentasi }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Tombol sudah divalidasi -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection