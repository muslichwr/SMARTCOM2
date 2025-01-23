@extends('layouts.admin')

@section('title', 'Detail Sintaks Kelompok PJBL')

@section('content')
<div class="flex justify-center mt-5">
    <div class="w-full mt-3">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg mt-3">
            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                <h3 class="text-lg font-semibold">Detail Sintaks Kelompok PJBL - {{ $materi->judul }}</h3>
                <a href="{{ route('admin.pjbl.sintaks.kelompok', $materi) }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-arrow-left class="w-5" />
                    Kembali ke Daftar Kelompok
                </a>
            </div>
            <div class="p-4 mt-3">
                <h4 class="text-lg font-semibold mb-3">Kelompok: {{ $kelompok->nama }}</h4>

                <!-- Tampilkan Data Setiap Tahap -->
                @foreach ($sintaks as $sintak)
                    <div class="mb-6 border rounded-lg p-4 bg-gray-50">
                        <h5 class="font-semibold text-lg mb-2">Tahap {{ str_replace('tahap_', '', $sintak->status_tahap) }}</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Data Tahap -->
                            <div>
                                @if ($sintak->status_tahap === 'tahap_1')
                                    <p><strong>Orientasi Masalah:</strong> {{ $sintak->orientasi_masalah }}</p>
                                    <p><strong>Rumusan Masalah:</strong> {{ $sintak->rumusan_masalah }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_2')
                                    <p><strong>Indikator Masalah:</strong> {{ $sintak->indikator_masalah }}</p>
                                    <p><strong>Hasil Analisis:</strong> {{ $sintak->hasil_analisis }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_3')
                                    <p><strong>Tugas Anggota:</strong> {{ json_decode($sintak->tugas_anggota, true) }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_4')
                                    <p><strong>File Jadwal:</strong> <a href="{{ asset('storage/' . $sintak->file_jadwal) }}" target="_blank" class="text-blue-500">Download</a></p>
                                @elseif ($sintak->status_tahap === 'tahap_5')
                                    <p><strong>To-Do List:</strong> {{ json_decode($sintak->to_do_list, true) }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_6')
                                    <p><strong>File Proyek:</strong> <a href="{{ asset('storage/' . $sintak->file_proyek) }}" target="_blank" class="text-blue-500">Download</a></p>
                                    <p><strong>File Laporan:</strong> <a href="{{ asset('storage/' . $sintak->file_laporan) }}" target="_blank" class="text-blue-500">Download</a></p>
                                @endif
                            </div>

                            <!-- Form Validasi dan Feedback -->
                            <div>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status_tahap" value="{{ $sintak->status_tahap }}">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                            <option value="valid" {{ $sintak->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintak->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ $sintak->status_validasi === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ $sintak->feedback_guru }}</textarea>
                                    </div>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection