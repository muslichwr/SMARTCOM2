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
                        <!-- Tahap 1 -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 1: Orientasi Masalah</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap1') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_1')->first() && $sintaks->where('status_tahap', 'tahap_1')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 2 -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 2: Indikator Masalah</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap2') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_2')->first() && $sintaks->where('status_tahap', 'tahap_2')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <!-- Tahap 3 -->
                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 3: Deskripsi Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap3') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_3')->first() && $sintaks->where('status_tahap', 'tahap_3')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 4: Menyusun Jadwal</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap4') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_4')->first() && $sintaks->where('status_tahap', 'tahap_4')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 5: Pelaksanaan Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap5') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_5')->first() && $sintaks->where('status_tahap', 'tahap_5')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 6: Pengumpulan Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap6') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_6')->first() && $sintaks->where('status_tahap', 'tahap_6')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-white shadow-md rounded-lg flex justify-between items-center">
                            <span class="font-semibold text-gray-800 text-lg">Tahap 7: Penilaian dan Evaluasi Proyek</span>
                            <div class="flex items-center">
                                <a href="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap7') }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                                    Mulai
                                </a>
                                @if ($sintaks->where('status_tahap', 'tahap_7')->first() && $sintaks->where('status_tahap', 'tahap_7')->first()->status_validasi == 'valid')
                                    <span class="ml-3 text-green-500 font-semibold">Sudah Divalidasi</span>
                                @else
                                    <span class="ml-3 text-red-500 font-semibold">Belum Divalidasi</span>
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
