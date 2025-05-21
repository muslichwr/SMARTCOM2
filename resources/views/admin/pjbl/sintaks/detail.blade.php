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
                <h4 class="text-lg font-semibold mb-3">Kelompok: {{ $kelompok->kelompok }}</h4>

                <!-- Tampilkan Data Setiap Tahap -->
                @foreach ($sintaks as $sintak)
                    <div class="mb-6 border rounded-lg p-4 bg-gray-50">
                        <h5 class="font-semibold text-lg mb-2">Tahap {{ str_replace('tahap_', '', $sintak->status_tahap) }}</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Data Tahap -->
                            <div>
                                @if ($sintak->status_tahap === 'tahap_1')
                                    <p><strong>Orientasi Masalah:</strong> {{ $sintak->orientasi_masalah ?? 'Belum diisi' }}</p>
                                    <p><strong>Rumusan Masalah:</strong> {{ $sintak->rumusan_masalah ?? 'Belum diisi' }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_2')
                                    <p><strong>Indikator Masalah:</strong> {{ $sintak->indikator_masalah ?? 'Belum diisi' }}</p>
                                    <p><strong>Hasil Analisis:</strong> {{ $sintak->hasil_analisis ?? 'Belum diisi' }}</p>
                                @elseif ($sintak->status_tahap === 'tahap_3')
                                    <p><strong>Deskripsi Proyek:</strong> {{ $sintak->deskripsi_proyek ?? 'Belum diisi' }}</p>
                                    <p><strong>Tugas Anggota:</strong></p>
                                    @if (!empty($sintak->tugas_anggota))
                                        <ul class="list-disc pl-6">
                                            @foreach (json_decode($sintak->tugas_anggota, true) as $key => $tugas)
                                                <li><strong>{{ $anggotaKelompok[$key]->user->name }}:</strong> {{ $tugas }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="pl-6">Belum diisi</p>
                                    @endif
                                @elseif ($sintak->status_tahap === 'tahap_4')
                                    <p><strong>File Jadwal:</strong> 
                                        @if (!empty($sintak->file_jadwal))
                                            <a href="{{ asset('storage/' . $sintak->file_jadwal) }}" target="_blank" class="text-blue-500">Download</a>
                                        @else
                                            Belum diupload
                                        @endif
                                    </p>
                                @elseif ($sintak->status_tahap === 'tahap_5')
                                    <p><strong>To-Do List:</strong></p>
                                    @if (!empty($sintak->to_do_list))
                                        <ul class="list-disc pl-6">
                                            @foreach (json_decode($sintak->to_do_list, true) as $todo)
                                                <li>
                                                    <strong>Tugas:</strong> {{ $todo['tugas'] ?? 'Tidak ada detail' }} <br>
                                                    <strong>Status:</strong> {{ $todo['status'] ?? 'Tidak ada status' }} <br>
                                                    <strong>Anggota:</strong> {{ $todo['anggota'] ?? 'Tidak ditugaskan' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="pl-6">Belum diisi</p>
                                    @endif
                                @elseif ($sintak->status_tahap === 'tahap_6')
                                    <p><strong>File Proyek:</strong> 
                                        @if (!empty($sintak->file_proyek))
                                            <a href="{{ asset('storage/' . $sintak->file_proyek) }}" target="_blank" class="text-blue-500">Download</a>
                                        @else
                                            Belum diupload
                                        @endif
                                    </p>
                                    <p><strong>File Laporan:</strong> 
                                        @if (!empty($sintak->file_laporan))
                                            <a href="{{ asset('storage/' . $sintak->file_laporan) }}" target="_blank" class="text-blue-500">Download</a>
                                        @else
                                            Belum diupload
                                        @endif
                                    </p>
                                @elseif ($sintak->status_tahap === 'tahap_7')
                                    <p><strong>Total Nilai:</strong> {{ $sintak->total_nilai ?? 'Belum dinilai' }}</p>
                                @endif
                            </div>

                            <!-- Form Validasi dan Feedback -->
                            <div>
                                @if ($sintak->status_tahap === 'tahap_7')
                                    <!-- Form Penilaian untuk Tahap 7 -->
                                    <form action="{{ route('admin.pjbl.sintaks.nilai', [$materi, $kelompok]) }}" method="POST">
                                        @csrf
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="score_class_object" class="block text-sm font-medium text-gray-700">Nilai Class dan Object (Maksimal 20)</label>
                                                <input type="number" name="score_class_object" id="score_class_object" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" min="0" max="20" value="{{ $sintak->score_class_object ?? 0 }}">
                                            </div>
                                            <div>
                                                <label for="score_encapsulation" class="block text-sm font-medium text-gray-700">Nilai Encapsulation (Maksimal 20)</label>
                                                <input type="number" name="score_encapsulation" id="score_encapsulation" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" min="0" max="20" value="{{ $sintak->score_encapsulation ?? 0 }}">
                                            </div>
                                            <div>
                                                <label for="score_inheritance" class="block text-sm font-medium text-gray-700">Nilai Inheritance (Maksimal 20)</label>
                                                <input type="number" name="score_inheritance" id="score_inheritance" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" min="0" max="20" value="{{ $sintak->score_inheritance ?? 0 }}">
                                            </div>
                                            <div>
                                                <label for="score_logic_function" class="block text-sm font-medium text-gray-700">Nilai Function and Logic (Maksimal 20)</label>
                                                <input type="number" name="score_logic_function" id="score_logic_function" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" min="0" max="20" value="{{ $sintak->score_logic_function ?? 0 }}">
                                            </div>
                                            <div>
                                                <label for="score_project_report" class="block text-sm font-medium text-gray-700">Nilai Project Report (Maksimal 20)</label>
                                                <input type="number" name="score_project_report" id="score_project_report" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" min="0" max="20" value="{{ $sintak->score_project_report ?? 0 }}">
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <label for="feedback_guru" class="block text-sm font-medium text-gray-700">Feedback Guru</label>
                                            <textarea name="feedback_guru" id="feedback_guru" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ $sintak->feedback_guru ?? '' }}</textarea>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                                Simpan Nilai dan Feedback
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <!-- Form Validasi dan Feedback untuk Tahap Lain -->
                                    <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status_tahap" value="{{ $sintak->status_tahap }}">
                                        <div class="mb-3">
                                            <label for="status_validasi" class="block text-sm font-medium text-gray-700">Status Validasi</label>
                                            <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                                <option value="valid" {{ $sintak->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                                <option value="invalid" {{ $sintak->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                                <option value="pending" {{ ($sintak->status_validasi === 'pending' || $sintak->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="feedback_guru" class="block text-sm font-medium text-gray-700">Feedback Guru</label>
                                            <textarea name="feedback_guru" id="feedback_guru" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">{{ $sintak->feedback_guru ?? '' }}</textarea>
                                        </div>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                            Simpan Validasi dan Feedback
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection