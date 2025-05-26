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
                
                <!-- Navigasi Tahapan Sintaks -->
                <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="font-semibold text-gray-700">Navigasi Tahapan</h5>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <select id="sintaks-navigator" class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" onchange="navigateToSintaks(this.value)">
                            <option value="">-- Pilih Tahapan --</option>
                            <option value="tahap1">Tahap 1: Orientasi Masalah</option>
                            <option value="tahap2">Tahap 2: Rancangan Proyek</option>
                            <option value="tahap3">Tahap 3: Jadwal Proyek</option>
                            <option value="tahap4">Tahap 4: Pelaksanaan Proyek</option>
                            <option value="tahap5">Tahap 5: Hasil Karya Proyek</option>
                            <option value="tahap6">Tahap 6: Presentasi Proyek</option>
                            <option value="tahap7">Tahap 7: Penilaian Kelompok dan Individu</option>
                            <option value="tahap8">Tahap 8: Evaluasi dan Refleksi</option>
                        </select>
                        
                        <div class="flex gap-2">
                            <a href="#tahap1" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T1</a>
                            <a href="#tahap2" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T2</a>
                            <a href="#tahap3" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T3</a>
                            <a href="#tahap4" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T4</a>
                        </div>
                        <div class="flex gap-2">
                            <a href="#tahap5" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T5</a>
                            <a href="#tahap6" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T6</a>
                            <a href="#tahap7" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T7</a>
                            <a href="#tahap8" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center px-2 py-2 text-xs rounded">T8</a>
                        </div>
                        
                        <!-- Status Overview -->
                        <div class="col-span-2 sm:col-span-4 mt-2 p-3 border rounded-lg bg-gray-50">
                            <div class="grid grid-cols-4 gap-2">
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapSatu && $sintaks->sintaksTahapSatu->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapSatu && $sintaks->sintaksTahapSatu->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T1
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapDua && $sintaks->sintaksTahapDua->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapDua && $sintaks->sintaksTahapDua->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T2
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapTiga && $sintaks->sintaksTahapTiga->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapTiga && $sintaks->sintaksTahapTiga->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T3
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapEmpat && $sintaks->sintaksTahapEmpat->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapEmpat && $sintaks->sintaksTahapEmpat->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T4
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapLima && $sintaks->sintaksTahapLima->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapLima && $sintaks->sintaksTahapLima->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T5
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T6
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T7
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi === 'valid' ? 'bg-green-200 text-green-800' : ($sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi === 'invalid' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        T8
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tahap 1: Orientasi Masalah -->
                @if($sintaks->sintaksTahapSatu)
                    <div id="tahap1" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 1: Orientasi Masalah</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <!-- Status Orientasi Masalah -->
                                <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex justify-between mb-2">
                                        <p class="font-medium text-gray-700">Status Orientasi</p>
                                        @php
                                            $statusText = '';
                                            $statusColor = '';
                                            
                                            if ($sintaks->sintaksTahapSatu->status_validasi === 'valid') {
                                                $statusText = 'Valid';
                                                $statusColor = 'green';
                                            } elseif ($sintaks->sintaksTahapSatu->status_validasi === 'invalid') {
                                                $statusText = 'Invalid';
                                                $statusColor = 'red';
                                            } else {
                                                $statusText = 'Pending';
                                                $statusColor = 'yellow';
                                            }
                                        @endphp
                                        <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Detail Orientasi Masalah -->
                                <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                    <h6 class="font-medium text-gray-700 mb-3">Orientasi Masalah</h6>
                                    <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="tahap" value="tahap_satu">
                                        <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapSatu->status_validasi }}">
                                        <textarea name="orientasi_masalah" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapSatu->orientasi_masalah ?? '' }}</textarea>
                                        <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                            Simpan Orientasi Masalah
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- Rumusan Masalah -->
                                <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                    <h6 class="font-medium text-gray-700 mb-3">Rumusan Masalah</h6>
                                    <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapSatu->rumusan_masalah ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>
                                
                                <!-- File Indikator dan Hasil Analisis -->
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h6 class="font-medium text-gray-700 mb-3">File Pendukung</h6>
                                    
                                    @if($sintaks->sintaksTahapSatu->file_indikator_masalah)
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700">File Indikator Masalah:</p>
                                            <div class="flex items-center mt-2">
                                                <a href="{{ asset('storage/' . $sintaks->sintaksTahapSatu->file_indikator_masalah) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <a href="{{ asset('storage/' . $sintaks->sintaksTahapSatu->file_indikator_masalah) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </a>
                                            </div>
                                            <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="tahap" value="tahap_satu">
                                                <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapSatu->status_validasi }}">
                                                <label for="file_indikator_masalah" class="block text-sm font-medium text-gray-700 mb-1">Ganti File Indikator Masalah:</label>
                                                <input type="file" name="file_indikator_masalah" id="file_indikator_masalah" class="block w-full text-sm border border-gray-300 rounded-md">
                                                <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                                    Upload File Baru
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    
                                    @if($sintaks->sintaksTahapSatu->file_hasil_analisis)
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700">File Hasil Analisis:</p>
                                            <div class="flex items-center mt-2">
                                                <a href="{{ asset('storage/' . $sintaks->sintaksTahapSatu->file_hasil_analisis) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                                <a href="{{ asset('storage/' . $sintaks->sintaksTahapSatu->file_hasil_analisis) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <p class="text-sm font-medium text-gray-700">File Hasil Analisis:</p>
                                            <p class="text-sm text-gray-500 mt-1">Belum diunggah oleh siswa</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Form Validasi dan Feedback -->
                            <div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                    <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tahap" value="tahap_satu">
                                        <div class="mb-3">
                                            <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                            <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                                <option value="valid" {{ $sintaks->sintaksTahapSatu->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                                <option value="invalid" {{ $sintaks->sintaksTahapSatu->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                                <option value="pending" {{ ($sintaks->sintaksTahapSatu->status_validasi === 'pending' || $sintaks->sintaksTahapSatu->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                            <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapSatu->feedback_guru ?? '' }}</textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                            Simpan Validasi dan Feedback
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tahap 2: Rancangan Proyek -->
                @if($sintaks->sintaksTahapDua)
                <div id="tahap2" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 2: Rancangan Proyek</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Status Rancangan Proyek -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Status Rancangan</p>
                                    @php
                                        $statusText = '';
                                        $statusColor = '';
                                        
                                        if ($sintaks->sintaksTahapDua->status_validasi === 'valid') {
                                            $statusText = 'Valid';
                                            $statusColor = 'green';
                                        } elseif ($sintaks->sintaksTahapDua->status_validasi === 'invalid') {
                                            $statusText = 'Invalid';
                                            $statusColor = 'red';
                                        } else {
                                            $statusText = 'Pending';
                                            $statusColor = 'yellow';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Detail Rancangan -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Deskripsi Rancangan</h6>
                                <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapDua->deskripsi_rancangan ?? 'Belum diisi' }}</p>
                                </div>
                            </div>
                            
                            <!-- File Rancangan -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">File Rancangan</h6>
                                
                                @if($sintaks->sintaksTahapDua->file_rancangan)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">File Rancangan:</p>
                                        <div class="flex items-center mt-2">
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapDua->file_rancangan) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapDua->file_rancangan) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="tahap" value="tahap_dua">
                                            <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapDua->status_validasi }}">
                                            <label for="file_rancangan" class="block text-sm font-medium text-gray-700 mb-1">Ganti File Rancangan:</label>
                                            <input type="file" name="file_rancangan" id="file_rancangan" class="block w-full text-sm border border-gray-300 rounded-md">
                                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                                Upload File Baru
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Upload File Rancangan:</p>
                                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="tahap" value="tahap_dua">
                                            <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapDua->status_validasi }}">
                                            <input type="file" name="file_rancangan" id="file_rancangan" class="block w-full text-sm border border-gray-300 rounded-md">
                                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                                Upload File
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_dua">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapDua->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapDua->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ ($sintaks->sintaksTahapDua->status_validasi === 'pending' || $sintaks->sintaksTahapDua->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapDua->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tahap 3: Jadwal Proyek -->
                @if($sintaks->sintaksTahapTiga)
                <div id="tahap3" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 3: Jadwal Proyek</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Status Jadwal Proyek -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Status Jadwal</p>
                                    @php
                                        $statusText = '';
                                        $statusColor = '';
                                        
                                        if ($sintaks->sintaksTahapTiga->status_validasi === 'valid') {
                                            $statusText = 'Valid';
                                            $statusColor = 'green';
                                        } elseif ($sintaks->sintaksTahapTiga->status_validasi === 'invalid') {
                                            $statusText = 'Invalid';
                                            $statusColor = 'red';
                                        } else {
                                            $statusText = 'Pending';
                                            $statusColor = 'yellow';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Informasi Jadwal -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Informasi Jadwal</h6>
                                
                                <div class="grid grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Tanggal Mulai:</p>
                                        <p class="text-sm text-gray-900">{{ $sintaks->sintaksTahapTiga->tanggal_mulai ? date('d/m/Y', strtotime($sintaks->sintaksTahapTiga->tanggal_mulai)) : 'Belum diisi' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Tanggal Selesai:</p>
                                        <p class="text-sm text-gray-900">{{ $sintaks->sintaksTahapTiga->tanggal_selesai ? date('d/m/Y', strtotime($sintaks->sintaksTahapTiga->tanggal_selesai)) : 'Belum diisi' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Formulir Edit Tanggal -->
                                <div class="mt-3 border-t pt-3">
                                    <button type="button" id="toggleEditTanggal" class="text-blue-500 hover:text-blue-700 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Edit Tanggal
                                    </button>
                                    
                                    <div id="formEditTanggal" class="hidden mt-3">
                                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tahap" value="tahap_tiga">
                                            <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapTiga->status_validasi }}">
                                            
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                                        value="{{ $sintaks->sintaksTahapTiga->tanggal_mulai ? $sintaks->sintaksTahapTiga->tanggal_mulai->format('Y-m-d') : '' }}" 
                                                        class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                                </div>
                                                <div>
                                                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                                        value="{{ $sintaks->sintaksTahapTiga->tanggal_selesai ? $sintaks->sintaksTahapTiga->tanggal_selesai->format('Y-m-d') : '' }}" 
                                                        class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                    Simpan Perubahan
                                                </button>
                                                <button type="button" id="cancelEditTanggal" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 py-1 rounded text-sm">
                                                    Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                @if($sintaks->sintaksTahapTiga->file_jadwal)
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700">File Jadwal:</p>
                                        <div class="flex items-center mt-2">
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapTiga->file_jadwal) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapTiga->file_jadwal) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Pembagian Tugas Anggota -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Pembagian Tugas Anggota</h6>
                                
                                @if (!empty($sintaks->sintaksTahapTiga->tugas_anggota))
                                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                        <ul class="space-y-2">
                                            @foreach (json_decode($sintaks->sintaksTahapTiga->tugas_anggota, true) as $key => $tugas)
                                                <li>
                                                    <span class="text-sm font-medium">Anggota {{ $key+1 }}:</span>
                                                    <p class="text-sm text-gray-700">{{ $tugas }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Pembagian tugas belum diisi</p>
                                @endif
                            </div>
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_tiga">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapTiga->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapTiga->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ ($sintaks->sintaksTahapTiga->status_validasi === 'pending' || $sintaks->sintaksTahapTiga->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapTiga->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Script untuk Tahap 3 -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Toggle form edit tanggal
                        const toggleBtn = document.getElementById('toggleEditTanggal');
                        const editForm = document.getElementById('formEditTanggal');
                        const cancelBtn = document.getElementById('cancelEditTanggal');
                        
                        if (toggleBtn && editForm && cancelBtn) {
                            toggleBtn.addEventListener('click', function() {
                                editForm.classList.toggle('hidden');
                            });
                            
                            cancelBtn.addEventListener('click', function() {
                                editForm.classList.add('hidden');
                            });
                        }
                    });
                </script>
                @endif

                <!-- Tahap 4: Pelaksanaan Proyek -->
                @if($sintaks->sintaksTahapEmpat)
                <div id="tahap4" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 4: Pelaksanaan Proyek</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Progress pelaksanaan proyek -->
                            @php
                                $totalTasks = $sintaks->sintaksTahapEmpat->tasks->count();
                                $completedTasks = $sintaks->sintaksTahapEmpat->tasks->where('status', 'selesai')->count();
                                $inProgressTasks = $sintaks->sintaksTahapEmpat->tasks->where('status', 'proses')->count();
                                $notStartedTasks = $sintaks->sintaksTahapEmpat->tasks->where('status', 'belum_mulai')->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                                
                                // Tentukan warna progress bar berdasarkan persentase
                                $progressColor = 'bg-red-500';
                                if ($percentage >= 25) $progressColor = 'bg-orange-500';
                                if ($percentage >= 50) $progressColor = 'bg-yellow-500';
                                if ($percentage >= 75) $progressColor = 'bg-blue-500';
                                if ($percentage >= 100) $progressColor = 'bg-green-500';
                            @endphp
                            
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Progress Pelaksanaan</p>
                                    <p class="font-bold text-{{ $percentage >= 100 ? 'green' : 'blue' }}-600">{{ $percentage }}%</p>
                                </div>
                                
                                <div class="bg-gray-200 rounded-full h-4 mb-3">
                                    <div class="{{ $progressColor }} h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div class="bg-gray-100 rounded-lg p-2">
                                        <span class="text-gray-700 text-sm">Belum Mulai</span>
                                        <p class="font-bold text-gray-800">{{ $notStartedTasks }}</p>
                                    </div>
                                    <div class="bg-blue-100 rounded-lg p-2">
                                        <span class="text-blue-700 text-sm">Dalam Proses</span>
                                        <p class="font-bold text-blue-800">{{ $inProgressTasks }}</p>
                                    </div>
                                    <div class="bg-green-100 rounded-lg p-2">
                                        <span class="text-green-700 text-sm">Selesai</span>
                                        <p class="font-bold text-green-800">{{ $completedTasks }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form untuk menambahkan tugas baru -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-3">
                                    <h6 class="font-medium text-gray-700">Tambah Tugas Baru</h6>
                                    <button id="toggleAddTaskForm" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </div>
                                
                                <div id="addTaskForm" class="hidden">
                                    <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="tahap" value="tahap_empat">
                                        <input type="hidden" name="action" value="add_task">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                            <div>
                                                <label for="judul_task" class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                                                <input type="text" name="judul_task" id="judul_task" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                            </div>
                                            <div>
                                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                                                <select name="user_id" id="user_id" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                                    <option value="">Pilih Anggota</option>
                                                    @foreach($anggotaKelompok as $anggota)
                                                        <option value="{{ $anggota->user->id }}">{{ $anggota->user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="deskripsi_task" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tugas</label>
                                            <textarea name="deskripsi_task" id="deskripsi_task" rows="3" class="w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                            <div>
                                                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                                                <input type="date" name="deadline" id="deadline" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                            </div>
                                            <div>
                                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                                <select name="status" id="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                                    <option value="belum_mulai">Belum Mulai</option>
                                                    <option value="proses">Dalam Proses</option>
                                                    <option value="selesai">Selesai</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                                Tambah Tugas
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Daftar tugas pelaksanaan -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-3">
                                    <h6 class="font-medium text-gray-700">Daftar Tugas</h6>
                                    
                                    <!-- Filter tugas -->
                                    <div class="flex space-x-2">
                                        <select id="task-filter" class="text-sm border border-gray-300 rounded-md shadow-sm p-1">
                                            <option value="all">Semua</option>
                                            <option value="belum_mulai">Belum Mulai</option>
                                            <option value="proses">Dalam Proses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="terlambat">Terlambat</option>
                                        </select>
                                    </div>
                                </div>
                                
                                @if($sintaks->sintaksTahapEmpat->tasks->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-300">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Judul</th>
                                                    <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Penanggung Jawab</th>
                                                    <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Deadline</th>
                                                    <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Status</th>
                                                    <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sintaks->sintaksTahapEmpat->tasks as $task)
                                                    @php
                                                        $isLate = strtotime($task->deadline) < time() && $task->status != 'selesai';
                                                    @endphp
                                                    <tr 
                                                        class="task-row {{ $isLate ? 'bg-red-50' : '' }} hover:bg-gray-50" 
                                                        data-status="{{ $task->status }}" 
                                                        data-late="{{ $isLate ? 'yes' : 'no' }}"
                                                    >
                                                        <td class="py-2 px-3 border-b text-sm">
                                                            <div class="font-medium">{{ $task->judul_task }}</div>
                                                            @if($task->deskripsi_task)
                                                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($task->deskripsi_task, 50) }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="py-2 px-3 border-b text-sm">{{ $task->user->name }}</td>
                                                        <td class="py-2 px-3 border-b text-sm">
                                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d-m-Y') }}
                                                            @if($isLate)
                                                                <span class="text-red-500 text-xs ml-1">(Terlambat)</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-2 px-3 border-b text-sm">
                                                            @if($task->status == 'belum_mulai')
                                                                <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Belum Mulai</span>
                                                            @elseif($task->status == 'proses')
                                                                <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">Dalam Proses</span>
                                                            @elseif($task->status == 'selesai')
                                                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Selesai</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-2 px-3 border-b text-sm">
                                                            <div class="flex space-x-2">
                                                                <button type="button" class="text-blue-500 hover:text-blue-700 view-task-details" data-task-id="{{ $task->id }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                @if($task->file_tugas)
                                                                    <a href="{{ asset('storage/' . $task->file_tugas) }}" target="_blank" class="text-green-500 hover:text-green-700">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm">
                                        <p>Belum ada tugas yang ditambahkan. Gunakan form di atas untuk menambahkan tugas baru.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_empat">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapEmpat->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapEmpat->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ ($sintaks->sintaksTahapEmpat->status_validasi === 'pending' || $sintaks->sintaksTahapEmpat->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapEmpat->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Detail Task Modal -->
                            <div id="task-details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-semibold" id="modal-title">Detail Tugas</h3>
                                        <button type="button" class="text-gray-500 hover:text-gray-700" id="close-modal">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="modal-content">
                                        <!-- Detail tugas akan ditampilkan di sini -->
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm" id="close-modal-btn">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Script untuk Tahap 4 -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Toggle form tambah tugas
                        const toggleBtn = document.getElementById('toggleAddTaskForm');
                        const addTaskForm = document.getElementById('addTaskForm');
                        
                        if (toggleBtn && addTaskForm) {
                            toggleBtn.addEventListener('click', function() {
                                addTaskForm.classList.toggle('hidden');
                            });
                        }
                        
                        // Filter tugas
                        const taskFilter = document.getElementById('task-filter');
                        const taskRows = document.querySelectorAll('.task-row');
                        
                        if (taskFilter) {
                            taskFilter.addEventListener('change', function() {
                                const filterValue = this.value;
                                
                                taskRows.forEach(row => {
                                    const status = row.getAttribute('data-status');
                                    const isLate = row.getAttribute('data-late');
                                    
                                    if (filterValue === 'all') {
                                        row.classList.remove('hidden');
                                    } else if (filterValue === 'terlambat') {
                                        row.classList.toggle('hidden', isLate !== 'yes');
                                    } else {
                                        row.classList.toggle('hidden', status !== filterValue);
                                    }
                                });
                            });
                        }
                        
                        // Modal Detail Tugas
                        const modal = document.getElementById('task-details-modal');
                        const modalContent = document.getElementById('modal-content');
                        const modalTitle = document.getElementById('modal-title');
                        const closeModal = document.getElementById('close-modal');
                        const closeModalBtn = document.getElementById('close-modal-btn');
                        const viewButtons = document.querySelectorAll('.view-task-details');
                        
                        function openModal(title, content) {
                            modalTitle.textContent = title;
                            modalContent.innerHTML = content;
                            modal.classList.remove('hidden');
                        }
                        
                        function closeModalHandler() {
                            modal.classList.add('hidden');
                        }
                        
                        if (closeModal) closeModal.addEventListener('click', closeModalHandler);
                        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModalHandler);
                        
                        viewButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const taskId = this.getAttribute('data-task-id');
                                const taskRow = this.closest('tr');
                                const taskTitle = taskRow.querySelector('td:first-child .font-medium').textContent;
                                const taskDesc = taskRow.querySelector('td:first-child .text-xs')?.textContent || 'Tidak ada deskripsi';
                                const taskOwner = taskRow.querySelector('td:nth-child(2)').textContent;
                                const taskDeadline = taskRow.querySelector('td:nth-child(3)').textContent;
                                const taskStatus = taskRow.querySelector('td:nth-child(4) span').textContent;
                                
                                let content = `
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Penanggung Jawab:</p>
                                            <p class="text-sm text-gray-900">${taskOwner}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Deskripsi:</p>
                                            <p class="text-sm text-gray-900">${taskDesc}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Deadline:</p>
                                            <p class="text-sm text-gray-900">${taskDeadline}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Status:</p>
                                            <p class="text-sm text-gray-900">${taskStatus}</p>
                                        </div>
                                    </div>
                                `;
                                
                                openModal(taskTitle, content);
                            });
                        });
                    });
                </script>
                @endif

                <!-- Tahap 5: Hasil Karya Proyek -->
                @if($sintaks->sintaksTahapLima)
                <div id="tahap5" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 5: Hasil Karya Proyek</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Status Hasil Karya Proyek -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Status Hasil Karya</p>
                                    @php
                                        $statusText = '';
                                        $statusColor = '';
                                        
                                        if ($sintaks->sintaksTahapLima->status_validasi === 'valid') {
                                            $statusText = 'Valid';
                                            $statusColor = 'green';
                                        } elseif ($sintaks->sintaksTahapLima->status_validasi === 'invalid') {
                                            $statusText = 'Invalid';
                                            $statusColor = 'red';
                                        } else {
                                            $statusText = 'Pending';
                                            $statusColor = 'yellow';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                        {{ $statusText }}
                                    </span>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Status Pengerjaan:</p>
                                    @if($sintaks->sintaksTahapLima->status == 'belum_mulai')
                                        <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs">Belum Mulai</span>
                                    @elseif($sintaks->sintaksTahapLima->status == 'proses')
                                        <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded-full text-xs">Dalam Proses</span>
                                    @elseif($sintaks->sintaksTahapLima->status == 'selesai')
                                        <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Selesai</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Detail Deskripsi Hasil -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Deskripsi Hasil Karya</h6>
                                <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapLima->deskripsi_hasil ?? 'Belum diisi' }}</p>
                                </div>
                            </div>
                            
                            <!-- File Hasil Karya -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">File Hasil Karya</h6>
                                
                                @if($sintaks->sintaksTahapLima->file_hasil_karya)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">File Hasil:</p>
                                        <div class="flex items-center mt-2">
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapLima->file_hasil_karya) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                <i class="fas fa-download mr-1"></i> Download
                                            </a>
                                            <a href="{{ asset('storage/' . $sintaks->sintaksTahapLima->file_hasil_karya) }}" target="_blank" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="tahap" value="tahap_lima">
                                            <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapLima->status_validasi }}">
                                            <label for="file_hasil_karya" class="block text-sm font-medium text-gray-700 mb-1">Ganti File Hasil Karya:</label>
                                            <input type="file" name="file_hasil_karya" id="file_hasil_karya" class="block w-full text-sm border border-gray-300 rounded-md">
                                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                                Upload File Baru
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Upload File Hasil Karya:</p>
                                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="tahap" value="tahap_lima">
                                            <input type="hidden" name="status_validasi" value="{{ $sintaks->sintaksTahapLima->status_validasi }}">
                                            <input type="file" name="file_hasil_karya" id="file_hasil_karya" class="block w-full text-sm border border-gray-300 rounded-md">
                                            <button type="submit" class="mt-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                                                Upload File
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_lima">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapLima->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapLima->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ ($sintaks->sintaksTahapLima->status_validasi === 'pending' || $sintaks->sintaksTahapLima->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapLima->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tahap 6: Presentasi Proyek -->
                @if($sintaks->sintaksTahapEnam)
                <div id="tahap6" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 6: Presentasi Proyek</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Status Presentasi Proyek -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Status Presentasi</p>
                                    @php
                                        $statusText = '';
                                        $statusColor = '';
                                        
                                        if ($sintaks->sintaksTahapEnam->status_validasi === 'valid') {
                                            $statusText = 'Valid';
                                            $statusColor = 'green';
                                        } elseif ($sintaks->sintaksTahapEnam->status_validasi === 'invalid') {
                                            $statusText = 'Invalid';
                                            $statusColor = 'red';
                                        } else {
                                            $statusText = 'Pending';
                                            $statusColor = 'yellow';
                                        }
                                        
                                        $statusProgressText = 'Belum Mulai';
                                        $statusProgressColor = 'gray';
                                        
                                        if ($sintaks->sintaksTahapEnam->status == 'proses') {
                                            $statusProgressText = 'Dalam Proses';
                                            $statusProgressColor = 'blue';
                                        } elseif ($sintaks->sintaksTahapEnam->status == 'selesai') {
                                            $statusProgressText = 'Selesai';
                                            $statusProgressColor = 'green';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                        {{ $statusText }}
                                    </span>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Status Pengerjaan:</p>
                                    <span class="px-2 py-1 bg-{{ $statusProgressColor }}-100 text-{{ $statusProgressColor }}-800 rounded-full text-xs">
                                        {{ $statusProgressText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Detail Presentasi -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Detail Presentasi</h6>
                                
                                <!-- Link Presentasi -->
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700">Link Presentasi:</p>
                                    <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                        @if($sintaks->sintaksTahapEnam->link_presentasi)
                                            <a href="{{ $sintaks->sintaksTahapEnam->link_presentasi }}" target="_blank" class="text-blue-600 hover:underline break-words text-sm">
                                                {{ $sintaks->sintaksTahapEnam->link_presentasi }}
                                            </a>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum diisi</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Jadwal Presentasi -->
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700">Jadwal Presentasi:</p>
                                    <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                        @if($sintaks->sintaksTahapEnam->jadwal_presentasi)
                                            <p class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($sintaks->sintaksTahapEnam->jadwal_presentasi)->format('d F Y, H:i') }}
                                                <span class="text-xs text-gray-500 ml-1">
                                                    ({{ \Carbon\Carbon::parse($sintaks->sintaksTahapEnam->jadwal_presentasi)->diffForHumans() }})
                                                </span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum ditentukan</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Catatan Presentasi -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Catatan Presentasi:</p>
                                    <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                        @if($sintaks->sintaksTahapEnam->catatan_presentasi)
                                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapEnam->catatan_presentasi }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Tidak ada catatan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Form Edit Presentasi -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-3">
                                    <h6 class="font-medium text-gray-700">Edit Presentasi</h6>
                                    <button id="toggleEditPresentasi" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                
                                <div id="editPresentasiForm" class="hidden">
                                    <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="tahap" value="tahap_enam">
                                        
                                        <div class="mb-3">
                                            <label for="link_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Link Presentasi</label>
                                            <input type="url" name="link_presentasi" id="link_presentasi" 
                                                value="{{ $sintaks->sintaksTahapEnam->link_presentasi }}"
                                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="jadwal_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Jadwal Presentasi</label>
                                            <input type="datetime-local" name="jadwal_presentasi" id="jadwal_presentasi" 
                                                value="{{ $sintaks->sintaksTahapEnam->jadwal_presentasi ? date('Y-m-d\TH:i', strtotime($sintaks->sintaksTahapEnam->jadwal_presentasi)) : '' }}"
                                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="catatan_presentasi" class="block text-sm font-medium text-gray-700 mb-1">Catatan Presentasi</label>
                                            <textarea name="catatan_presentasi" id="catatan_presentasi" rows="3" 
                                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">{{ $sintaks->sintaksTahapEnam->catatan_presentasi }}</textarea>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_enam">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapEnam->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapEnam->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ ($sintaks->sintaksTahapEnam->status_validasi === 'pending' || $sintaks->sintaksTahapEnam->status_validasi === null) ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapEnam->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Script untuk Tahap 6 -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Toggle form edit presentasi
                        const toggleBtn = document.getElementById('toggleEditPresentasi');
                        const editForm = document.getElementById('editPresentasiForm');
                        
                        if (toggleBtn && editForm) {
                            toggleBtn.addEventListener('click', function() {
                                editForm.classList.toggle('hidden');
                            });
                        }
                    });
                </script>
                @endif

                <!-- Tahap 7: Penilaian Kelompok dan Individu -->
                @if($sintaks->sintaksTahapTuju || $sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi === 'valid')
                <div id="tahap7" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 7: Penilaian Kelompok dan Individu</h5>
                    
                    <!-- Status Penilaian -->
                    <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                        <div class="flex justify-between mb-2">
                            <p class="font-medium text-gray-700">Status Penilaian</p>
                            @php
                                $statusText = 'Belum Dimulai';
                                $statusColor = 'gray';
                                
                                if ($sintaks->sintaksTahapTuju) {
                                    if ($sintaks->sintaksTahapTuju->status_validasi == 'pending') {
                                        $statusText = 'Menunggu Penilaian';
                                        $statusColor = 'yellow';
                                    } elseif ($sintaks->sintaksTahapTuju->status_validasi == 'valid') {
                                        $statusText = 'Penilaian Selesai';
                                        $statusColor = 'green';
                                    } elseif ($sintaks->sintaksTahapTuju->status_validasi == 'invalid') {
                                        $statusText = 'Penilaian Ditolak';
                                        $statusColor = 'red';
                                    }
                                }
                            @endphp
                            <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Form Penilaian Batch (Semua Siswa) -->
                    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                        <h6 class="font-medium text-gray-700 mb-3">Penilaian Seluruh Anggota Kelompok</h6>
                        
                        @if(isset($anggotaKelompok) && $anggotaKelompok->isNotEmpty())
                            <form action="{{ route('admin.pjbl.sintaks.beri-nilai-batch', [$materi, $kelompok]) }}" method="POST" id="form-batch-penilaian">
                                @csrf
                                
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200 mb-4">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Pemahaman Konsep <br><span class="text-gray-400">(Bobot: 3)</span>
                                                </th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Implementasi <br><span class="text-gray-400">(Bobot: 4)</span>
                                                </th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Kreativitas <br><span class="text-gray-400">(Bobot: 2)</span>
                                                </th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Presentasi <br><span class="text-gray-400">(Bobot: 2)</span>
                                                </th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Dokumentasi <br><span class="text-gray-400">(Bobot: 1)</span>
                                                </th>
                                                <th class="py-2 px-3 border-b text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Total Nilai
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($anggotaKelompok as $index => $anggota)
                                                @php
                                                    $nilaiAnggota = null;
                                                    $nilaiPemahaman = '';
                                                    $nilaiImplementasi = '';
                                                    $nilaiKreativitas = '';
                                                    $nilaiPresentasi = '';
                                                    $nilaiDokumentasi = '';
                                                    $totalNilai = '';
                                                    
                                                    if ($sintaks->sintaksTahapTuju) {
                                                        $nilaiAnggota = $sintaks->sintaksTahapTuju->nilaiIndividu()
                                                            ->where('user_id', $anggota->user->id)
                                                            ->first();
                                                            
                                                        if ($nilaiAnggota) {
                                                            $nilaiKriteria = $nilaiAnggota->nilai_kriteria['kriteria'] ?? [];
                                                            foreach ($nilaiKriteria as $kriteria) {
                                                                if ($kriteria['nama'] == 'Pemahaman Konsep') {
                                                                    $nilaiPemahaman = $kriteria['nilai'];
                                                                } elseif ($kriteria['nama'] == 'Implementasi') {
                                                                    $nilaiImplementasi = $kriteria['nilai'];
                                                                } elseif ($kriteria['nama'] == 'Kreativitas') {
                                                                    $nilaiKreativitas = $kriteria['nilai'];
                                                                } elseif ($kriteria['nama'] == 'Presentasi') {
                                                                    $nilaiPresentasi = $kriteria['nilai'];
                                                                } elseif ($kriteria['nama'] == 'Dokumentasi') {
                                                                    $nilaiDokumentasi = $kriteria['nilai'];
                                                                }
                                                            }
                                                            $totalNilai = $nilaiAnggota->total_nilai_individu;
                                                        }
                                                    }
                                                @endphp
                                                
                                                <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                    <td class="py-2 px-3 border-b">
                                                        <input type="hidden" name="nilai[{{ $index }}][user_id]" value="{{ $anggota->user->id }}">
                                                        <div>
                                                            <p class="font-medium">{{ $anggota->user->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $anggota->user->email }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][pemahaman]" value="{{ $nilaiPemahaman }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm nilai-input"
                                                            data-index="{{ $index }}" data-type="pemahaman" data-bobot="3" required>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][implementasi]" value="{{ $nilaiImplementasi }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm nilai-input"
                                                            data-index="{{ $index }}" data-type="implementasi" data-bobot="4" required>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][kreativitas]" value="{{ $nilaiKreativitas }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm nilai-input"
                                                            data-index="{{ $index }}" data-type="kreativitas" data-bobot="2" required>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][presentasi]" value="{{ $nilaiPresentasi }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm nilai-input"
                                                            data-index="{{ $index }}" data-type="presentasi" data-bobot="2" required>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][dokumentasi]" value="{{ $nilaiDokumentasi }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm nilai-input"
                                                            data-index="{{ $index }}" data-type="dokumentasi" data-bobot="1" required>
                                                    </td>
                                                    <td class="py-2 px-3 border-b text-center">
                                                        <input type="number" name="nilai[{{ $index }}][total]" value="{{ $totalNilai }}" 
                                                            min="0" max="100" class="w-16 border border-gray-300 rounded p-1 text-center text-sm font-bold bg-gray-100 total-nilai"
                                                            data-index="{{ $index }}" readonly>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Feedback -->
                                <div class="mb-4">
                                    <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Umum untuk Kelompok</label>
                                    <textarea name="feedback_guru" id="feedback_guru" rows="3" 
                                        class="w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm">{{ $sintaks->sintaksTahapTuju->feedback_guru ?? '' }}</textarea>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                                        Simpan Semua Nilai
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm">
                                <p>Tidak ada anggota kelompok yang ditemukan.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Form Status Validasi -->
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                        <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="tahap_tuju">
                            <div class="mb-3">
                                <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    <option value="valid" {{ $sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                    <option value="invalid" {{ $sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                    <option value="pending" {{ !$sintaks->sintaksTahapTuju || $sintaks->sintaksTahapTuju->status_validasi === 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                                Simpan Status Validasi
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Script untuk Tahap 7 -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Input nilai pada tabel
                        const nilaiInputs = document.querySelectorAll('.nilai-input');
                        
                        nilaiInputs.forEach(input => {
                            input.addEventListener('input', function() {
                                calculateTotalForStudent(this.dataset.index);
                            });
                            
                            // Inisialisasi total nilai jika input sudah terisi
                            if (input.value) {
                                calculateTotalForStudent(input.dataset.index);
                            }
                        });
                        
                        // Fungsi untuk menghitung total nilai per siswa
                        function calculateTotalForStudent(index) {
                            const pemahamanInput = document.querySelector(`input[name="nilai[${index}][pemahaman]"]`);
                            const implementasiInput = document.querySelector(`input[name="nilai[${index}][implementasi]"]`);
                            const kreativitasInput = document.querySelector(`input[name="nilai[${index}][kreativitas]"]`);
                            const presentasiInput = document.querySelector(`input[name="nilai[${index}][presentasi]"]`);
                            const dokumentasiInput = document.querySelector(`input[name="nilai[${index}][dokumentasi]"]`);
                            const totalInput = document.querySelector(`input[name="nilai[${index}][total]"]`);
                            
                            let totalNilaiTertimbang = 0;
                            let totalBobot = 0;
                            
                            // Pemahaman Konsep (bobot 3)
                            if (pemahamanInput.value) {
                                totalNilaiTertimbang += parseFloat(pemahamanInput.value) * 3;
                                totalBobot += 3;
                            }
                            
                            // Implementasi (bobot 4)
                            if (implementasiInput.value) {
                                totalNilaiTertimbang += parseFloat(implementasiInput.value) * 4;
                                totalBobot += 4;
                            }
                            
                            // Kreativitas (bobot 2)
                            if (kreativitasInput.value) {
                                totalNilaiTertimbang += parseFloat(kreativitasInput.value) * 2;
                                totalBobot += 2;
                            }
                            
                            // Presentasi (bobot 2)
                            if (presentasiInput.value) {
                                totalNilaiTertimbang += parseFloat(presentasiInput.value) * 2;
                                totalBobot += 2;
                            }
                            
                            // Dokumentasi (bobot 1)
                            if (dokumentasiInput.value) {
                                totalNilaiTertimbang += parseFloat(dokumentasiInput.value) * 1;
                                totalBobot += 1;
                            }
                            
                            // Hitung total nilai
                            const totalNilai = totalBobot > 0 ? Math.round((totalNilaiTertimbang / totalBobot) * 100) / 100 : 0;
                            totalInput.value = totalNilai;
                        }
                    });
                </script>
                @endif

                <!-- Tahap 8: Evaluasi dan Refleksi -->
                @if($sintaks->sintaksTahapDelapan || $sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi === 'valid')
                <div id="tahap8" class="mb-6 border rounded-lg p-4 bg-gray-50">
                    <h5 class="font-semibold text-lg mb-2">Tahap 8: Evaluasi dan Refleksi</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <!-- Status Evaluasi dan Refleksi -->
                            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between mb-2">
                                    <p class="font-medium text-gray-700">Status Evaluasi</p>
                                    @php
                                        $statusText = 'Belum Dimulai';
                                        $statusColor = 'gray';
                                        
                                        if ($sintaks->sintaksTahapDelapan) {
                                            if ($sintaks->sintaksTahapDelapan->status == 'proses') {
                                                $statusText = 'Dalam Proses';
                                                $statusColor = 'blue';
                                            } elseif ($sintaks->sintaksTahapDelapan->status == 'selesai') {
                                                $statusText = 'Selesai';
                                                $statusColor = 'green';
                                            }
                                        }
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full text-xs">
                                        {{ $statusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Evaluasi Kelompok -->
                            @if($sintaks->sintaksTahapDelapan)
                                <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                                    <h6 class="font-medium text-gray-700 mb-3">Evaluasi Kelompok</h6>
                                    
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Evaluasi Kelompok:</p>
                                        <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapDelapan->evaluasi_kelompok ?: 'Belum diisi' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700">Refleksi Pembelajaran:</p>
                                        <div class="mt-1 bg-gray-50 p-3 rounded border border-gray-200">
                                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $sintaks->sintaksTahapDelapan->refleksi_pembelajaran ?: 'Belum diisi' }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Refleksi Individu -->
                                <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                                    <h6 class="font-medium text-gray-700 mb-3">Refleksi Individu</h6>
                                    
                                    @if($sintaks->sintaksTahapDelapan->refleksiIndividu->count() > 0)
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full border border-gray-300">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Anggota</th>
                                                        <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Status</th>
                                                        <th class="py-2 px-3 border-b text-left text-xs font-medium text-gray-700">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($sintaks->sintaksTahapDelapan->refleksiIndividu as $refleksi)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="py-2 px-3 border-b text-sm">{{ $refleksi->user->name }}</td>
                                                            <td class="py-2 px-3 border-b text-sm">
                                                                @if($refleksi->refleksi_pribadi && $refleksi->kendala_dihadapi && $refleksi->pembelajaran_didapat)
                                                                    <span class="px-2 py-1 bg-green-200 text-green-800 rounded-full text-xs">Lengkap</span>
                                                                @else
                                                                    <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs">Belum Lengkap</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-2 px-3 border-b text-sm">
                                                                <button type="button" class="text-blue-500 hover:text-blue-700 view-refleksi" 
                                                                        data-refleksi-id="{{ $refleksi->id }}"
                                                                        data-name="{{ $refleksi->user->name }}"
                                                                        data-refleksi="{{ $refleksi->refleksi_pribadi }}"
                                                                        data-kendala="{{ $refleksi->kendala_dihadapi }}"
                                                                        data-pembelajaran="{{ $refleksi->pembelajaran_didapat }}">
                                                                    <i class="fas fa-eye"></i> Lihat
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm">
                                            <p>Belum ada refleksi individu yang ditambahkan.</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm">
                                    <p>Tahap 8 belum dimulai oleh kelompok.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Validasi dan Feedback -->
                        <div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h6 class="font-medium text-gray-700 mb-3">Status Tahapan</h6>
                                <form action="{{ route('admin.pjbl.sintaks.validasi', [$materi, $kelompok]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tahap" value="tahap_delapan">
                                    <div class="mb-3">
                                        <label for="status_validasi" class="block text-sm font-medium text-gray-700 mb-1">Status Validasi</label>
                                        <select name="status_validasi" id="status_validasi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                            <option value="valid" {{ $sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi === 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="invalid" {{ $sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi === 'invalid' ? 'selected' : '' }}>Invalid</option>
                                            <option value="pending" {{ !$sintaks->sintaksTahapDelapan || $sintaks->sintaksTahapDelapan->status_validasi === 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="feedback_guru" class="block text-sm font-medium text-gray-700 mb-1">Feedback Guru</label>
                                        <textarea name="feedback_guru" id="feedback_guru" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ $sintaks->sintaksTahapDelapan->feedback_guru ?? '' }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                                        Simpan Validasi dan Feedback
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Refleksi Individu -->
                <div id="refleksi-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold" id="refleksi-modal-title">Refleksi Individu</h3>
                            <button type="button" class="text-gray-500 hover:text-gray-700" id="close-refleksi-modal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div id="refleksi-modal-content">
                            <!-- Detail refleksi akan ditampilkan di sini -->
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm" id="close-refleksi-modal-btn">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Script untuk Modal Refleksi -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('refleksi-modal');
                        const modalTitle = document.getElementById('refleksi-modal-title');
                        const modalContent = document.getElementById('refleksi-modal-content');
                        const closeModalBtns = document.querySelectorAll('#close-refleksi-modal, #close-refleksi-modal-btn');
                        const viewButtons = document.querySelectorAll('.view-refleksi');
                        
                        function openModal(title, content) {
                            modalTitle.textContent = title;
                            modalContent.innerHTML = content;
                            modal.classList.remove('hidden');
                        }
                        
                        function closeModal() {
                            modal.classList.add('hidden');
                        }
                        
                        closeModalBtns.forEach(btn => {
                            btn.addEventListener('click', closeModal);
                        });
                        
                        viewButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const name = this.getAttribute('data-name');
                                const refleksi = this.getAttribute('data-refleksi') || 'Belum diisi';
                                const kendala = this.getAttribute('data-kendala') || 'Belum diisi';
                                const pembelajaran = this.getAttribute('data-pembelajaran') || 'Belum diisi';
                                
                                let content = `
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Refleksi Pribadi:</p>
                                            <div class="mt-1 bg-gray-50 p-2 rounded border border-gray-200">
                                                <p class="text-sm text-gray-900 whitespace-pre-line">${refleksi}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Kendala yang Dihadapi:</p>
                                            <div class="mt-1 bg-gray-50 p-2 rounded border border-gray-200">
                                                <p class="text-sm text-gray-900 whitespace-pre-line">${kendala}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Pembelajaran yang Didapat:</p>
                                            <div class="mt-1 bg-gray-50 p-2 rounded border border-gray-200">
                                                <p class="text-sm text-gray-900 whitespace-pre-line">${pembelajaran}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                openModal(`Refleksi ${name}`, content);
                            });
                        });
                    });
                </script>
                @endif

                <script>
                    function navigateToSintaks(value) {
                        if (value) {
                            const element = document.getElementById(value);
                            if (element) {
                                // Scroll to element with a small offset
                                window.scrollTo({
                                    top: element.offsetTop - 100,
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection