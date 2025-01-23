@extends('layouts.appF')

@section('title', 'Materi Page')

@section('content')

    <div class="bg-white min-h-screen flex items-center justify-center">

        {{-- Awal Materi --}}

        <div class="flex justify-center mt-5">
            <div class="w-full max-w-3xl mt-3">
                @if (session('message'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="bg-white shadow-md rounded-lg mt-3">
                    <div class="bg-gray-200 px-4 py-3 rounded-t-lg flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Daftar Bab Materi - {{ $judulMateri }}</h3>
                        
                        <div class="flex gap-2">
                            <a href="{{ url('user/materi') }}"
                                class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded w-max">
                                <x-heroicon-o-arrow-long-left class="w-5" />
                                Kembali
                            </a>
                            <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded">
                                Lihat Sintaks Materi
                            </a>
                        </div>
                    </div>
                    <br>
                    <div class="p-4 mt-3">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Judul Materi</th>
                                        <th class="px-4 py-2">Aksi</th>
                                        <th class="px-4 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($babs as $bab)
                                        <tr class="text-center">
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ $bab->judul }}</td>
                                            <td class="border px-4 py-2">

                                                {{-- BATAS EDIT --}}
                                                @if ($bab->status == 0)
                                                    @if (!$semuaBabsSelesai && (!$babAttempts || $babAttempts->count() == 0 || $babAttempts[0]->status != 1))
                                                        @if ($loop->first)
                                                            @php
                                                                $materiDiAtasnya = null;

                                                                // Periksa apakah ini bukan bab pertama
                                                                if ($loop->index > 0) {
                                                                    $materiDiAtasnya = $babs[$loop->index - 1];
                                                                }

                                                                $materiDiAtasnyaSudahDipelajari = $materiDiAtasnya && $materiDiAtasnya->status === 0;
                                                            @endphp
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Materi
                                                                    Sebelumnya</span>
                                                            @else
                                                                {{-- ini yang pertama --}}
                                                                <a href="{{ url('user/materi/' . $bab->slug . '/pelajari') }}"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Pelajari
                                                                    Materi</a>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-500">Selesaikan Materi Sebelumnya</span>
                                                        @endif
                                                    @else
                                                        @php
                                                            $previousAttempt = null;

                                                            // Periksa apakah ini bukan bab pertama
                                                            if ($loop->index > 0) {
                                                                $previousBab = $babs[$loop->index - 1];
                                                                $previousAttempt = $babAttempts->where('bab_id', $previousBab->id)->first();
                                                            }

                                                            $materiDiAtasnyaSudahDipelajari = $previousAttempt && $previousAttempt->status === 1;

                                                        @endphp
                                                        @if ($babAttempts->where('bab_id', $bab->id)->first())
                                                            <a href="{{ url('user/materi/' . $bab->slug . '/pelajari') }}"
                                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Pelajari
                                                                Ulang Materi</a>
                                                        @else
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                {{-- INI YANG KEDUA --}}
                                                                <a href="{{ url('user/materi/' . $bab->slug . '/pelajari') }}"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Pelajari
                                                                    Materi</a>
                                                            @elseif (!$materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Materi
                                                                    Sebelumnya</span>
                                                            @else
                                                                ada yg salah
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-gray-500">Selesaikan Materi Sebelumnya</span>
                                                @endif
                                            </td>


                                            {{-- STATUS --}}
                                            <td class="border px-4 py-2">
                                                @if ($bab->status == 0)
                                                    @if (!$semuaBabsSelesai && (!$babAttempts || $babAttempts->count() == 0 || $babAttempts[0]->status != 1))
                                                        @if ($loop->first)
                                                            @php
                                                                $materiDiAtasnya = null;

                                                                // Periksa apakah ini bukan bab pertama
                                                                if ($loop->index > 0) {
                                                                    $materiDiAtasnya = $babs[$loop->index - 1];
                                                                }

                                                                $materiDiAtasnyaSudahDipelajari = $materiDiAtasnya && $materiDiAtasnya->status === 0;
                                                            @endphp
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Materi
                                                                    Sebelumnya</span>
                                                            @else
                                                                {{-- ini yang pertama --}}
                                                                <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                                                            @endif
                                                        @else
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                                                        @endif
                                                    @else
                                                        @php
                                                            $previousAttempt = null;

                                                            // Periksa apakah ini bukan bab pertama
                                                            if ($loop->index > 0) {
                                                                $previousBab = $babs[$loop->index - 1];
                                                                $previousAttempt = $babAttempts->where('bab_id', $previousBab->id)->first();
                                                            }

                                                            $materiDiAtasnyaSudahDipelajari = $previousAttempt && $previousAttempt->status === 1;

                                                        @endphp
                                                        @if ($babAttempts->where('bab_id', $bab->id)->first())
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                                                            </svg>
                                                        @else
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                {{-- INI YANG KEDUA --}}
                                                                <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                                                                </svg>
                                                            @elseif (!$materiDiAtasnyaSudahDipelajari)
                                                                <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z" />
                                                                </svg>
                                                            @else
                                                                ada yg salah
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-gray-500">Selesaikan Materi Sebelumnya</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{-- LATIHAN --}}
                            @if ($bolehLatihan->isNotEmpty())
                                @php
                                    foreach ($bolehLatihan as $latihan) {
                                        $materi_id = $latihan->materi_id;
                                        // Lakukan sesuatu dengan $materi_id
                                        // dd($materi_id);
                                    }
                                @endphp
                                {{-- @if ($materis)
                                    @php
                                        $materi = $materis->first();
                                    @endphp --}}
                                <span>
                                    Anda telah berhasil menyelesaikan semua materi
                                    <a href="{{ url('user/latihan/' . $materi->slug) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Kerjakan
                                        Latihan</a>
                                </span>
                                {{-- @endif --}}
                            @else
                                <span>Sebelum memulai latihan anda harus menyelesaikan semua materi terlebih dahulu</span>
                            @endif
                        </div>
                        <div class="mt-4">
                            {{-- {{ $materis->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Akhir Materi --}}
    </div>
@endsection
