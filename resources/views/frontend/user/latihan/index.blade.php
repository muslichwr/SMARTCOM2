@extends('layouts.appF')

@section('title', 'Latihan Page')

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
                    <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                        <center>
                            <h3 class="text-lg font-semibold">Latihan Materi - {{ $judulMateri }}</h3>
                        </center>
                        <a href="{{ url('user/materi') }}"
                            class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded mt-5 w-max">
                            <x-heroicon-o-arrow-long-left class="w-5" />
                            Kembali
                        </a>
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
                                    @foreach ($latihans as $index => $latihan)
                                        <tr class="text-center">
                                            <td class="border px-4 py-2">{{ $latihans->firstItem() + $index }}</td>
                                            <td class="border px-4 py-2">{{ $latihan->judulLatihan }}</td>
                                            <td class="border px-4 py-2">
                                                {{-- BATAS EDIT --}}
                                                @if ($latihan->status == 0)
                                                    @if (
                                                        !$semuaLatihansSelesai &&
                                                            (!$latihanAttempts ||
                                                                $latihanAttempts->isEmpty() ||
                                                                $latihanAttempts->sortByDesc('updated_at')->first()->status != 2))
                                                        @if ($loop->first)
                                                            @php
                                                                $materiDiAtasnya = null;

                                                                // Periksa apakah ini bukan bab pertama
                                                                if ($loop->index > 0) {
                                                                    $materiDiAtasnya = $latihans[$loop->index - 1];
                                                                }

                                                                $materiDiAtasnyaSudahDipelajari = $materiDiAtasnya && $materiDiAtasnya->status === 0;
                                                            @endphp
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Latihan
                                                                    Sebelumnya</span>
                                                            @else
                                                                {{-- ini yang pertama --}}
                                                                <a href="{{ url('user/latihan/' . $latihan->slug . '/kerjakan') }}"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Kerjakan
                                                                    Latihan</a>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-500">Selesaikan Latihan Sebelumnya
                                                                </span>
                                                        @endif
                                                    @else
                                                        @php
                                                            $previousAttempt = null;

                                                            // Periksa apakah ini bukan bab pertama
                                                            if ($loop->index > 0) {
                                                                $previousBab = $latihans[$loop->index - 1];
                                                                $previousAttempt = $latihanAttempts
                                                                    ->where('latihan_id', $previousBab->id)
                                                                    ->sortByDesc('updated_at')
                                                                    ->first();
                                                            }

                                                            $materiDiAtasnyaSudahDipelajari = $previousAttempt && $previousAttempt->status === 2;

                                                        @endphp
                                                        @if ($latihanAttempts->where('latihan_id', $latihan->id)->first())
                                                            <a href="{{ url('user/latihan/' . $latihan->slug . '/kerjakan') }}"
                                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Koreksi
                                                                Latihan</a>
                                                        @else
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                {{-- INI YANG KEDUA --}}
                                                                <a href="{{ url('user/latihan/' . $latihan->slug . '/kerjakan') }}"
                                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Kerjakan
                                                                    Latihan</a>
                                                            @elseif (!$materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Latihan
                                                                    Sebelumnya </span>
                                                            @else
                                                                ada yang salah
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-gray-500">Selesaikan Latihan Sebelumnya</span>
                                                @endif
                                            </td>

                                            {{-- STATUS --}}
                                            <td class="border px-4 py-2">
                                                @if ($latihan->status == 0)
                                                    @if (
                                                        !$semuaLatihansSelesai &&
                                                            (!$latihanAttempts ||
                                                                $latihanAttempts->isEmpty() ||
                                                                $latihanAttempts->sortByDesc('updated_at')->first()->status != 2))
                                                        @if ($loop->first)
                                                            @php
                                                                $materiDiAtasnya = null;

                                                                // Periksa apakah ini bukan bab pertama
                                                                if ($loop->index > 0) {
                                                                    $materiDiAtasnya = $latihans[$loop->index - 1];
                                                                }

                                                                $materiDiAtasnyaSudahDipelajari = $materiDiAtasnya && $materiDiAtasnya->status === 0;
                                                            @endphp
                                                            @if ($materiDiAtasnyaSudahDipelajari)
                                                                <span class="text-gray-500">Selesaikan Latihan
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
                                                                $previousBab = $latihans[$loop->index - 1];
                                                                $previousAttempt = $latihanAttempts->where('latihan_id', $previousBab->id)->first();
                                                            }

                                                            $materiDiAtasnyaSudahDipelajari = $previousAttempt && $previousAttempt->status === 2;

                                                        @endphp
                                                        @if ($latihanAttempts->where('latihan_id', $latihan->id)->first())
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
                                                    <span class="text-gray-500">Selesaikan Latihan Sebelumnya</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{-- LATIHAN --}}
                            {{-- @if ($bolehLatihan->isNotEmpty())
                                @if ($materis)
                                    @php
                                        $materi = $materis->first();
                                    @endphp
                                    <span>
                                        Anda telah berhasil menyelesaikan semua materi
                                        <a href="{{ url('user/latihan/' . $materi->id) }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Kerjakan
                                            Latihan</a>
                                    </span>
                                @endif
                            @else
                                <span>Sebelum memulai latihan anda harus menyelesaikan semua materi terlebih dahulu</span>
                            @endif --}}
                        </div>
                        <div class="mt-4">
                            {{-- {{ $latihans->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Akhir Materi --}}
    </div>
@endsection
