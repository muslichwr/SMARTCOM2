@extends('layouts.appF')

@section('title', 'Sintaks Materi')

@section('content')
    <br>
    <br>
    <br>
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Daftar Tahapan Sintaks - {{ $materi->judul }}</h3>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug) }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Materi
                    </a>
                </div>
                <br>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Tahap 1: Orientasi Masalah -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 1: Orientasi Masalah</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap1') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapSatu && $sintaks->sintaksTahapSatu->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapSatu && $sintaks->sintaksTahapSatu->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapSatu && $sintaks->sintaksTahapSatu->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 2: Rancangan Proyek -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 2: Rancangan Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap2') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapDua && $sintaks->sintaksTahapDua->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapDua && $sintaks->sintaksTahapDua->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapDua && $sintaks->sintaksTahapDua->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 3: Jadwal Proyek -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 3: Jadwal Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap3') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapTiga && $sintaks->sintaksTahapTiga->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapTiga && $sintaks->sintaksTahapTiga->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapTiga && $sintaks->sintaksTahapTiga->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 4: Pelaksanaan Proyek -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 4: Pelaksanaan Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap4') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapEmpat && $sintaks->sintaksTahapEmpat->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapEmpat && $sintaks->sintaksTahapEmpat->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapEmpat && $sintaks->sintaksTahapEmpat->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 5: Hasil Karya Proyek -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 5: Hasil Karya Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap5') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapLima && $sintaks->sintaksTahapLima->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapLima && $sintaks->sintaksTahapLima->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapLima && $sintaks->sintaksTahapLima->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 6: Presentasi Proyek -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 6: Presentasi Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap6') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapEnam && $sintaks->sintaksTahapEnam->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 7: Penilaian Kelompok dan Individu -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 7: Penilaian Kelompok dan Individu</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap7') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapTuju && $sintaks->sintaksTahapTuju->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Tahap 8: Evaluasi dan Refleksi -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 8: Evaluasi dan Refleksi</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap8') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @elseif ($sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi == 'pending')
                                    <span class="ml-3 text-yellow-500 font-semibold">Menunggu Validasi</span>
                                @elseif ($sintaks->sintaksTahapDelapan && $sintaks->sintaksTahapDelapan->status_validasi == 'invalid')
                                    <span class="ml-3 text-red-500 font-semibold">Ditolak</span>
                                @else
                                    <span class="ml-3 text-gray-500 font-semibold">Belum Dikerjakan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
        </div>
    </div>
@endsection
