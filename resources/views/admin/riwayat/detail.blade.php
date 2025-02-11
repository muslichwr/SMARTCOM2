@extends('layouts.admin')

@section('title', 'Progress Detail User Page')

@section('content')

<div class="flex justify-center mt-5">
    <div class="w-full mt-3">
        @if (session('message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                {{ session('message') }}
            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg mt-3">
            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                <center>
                    <h3 class="text-lg font-semibold">Detail Pengerjaan {{ $pp->judulPrePost }} - {{ $userName->name }}</h3>                            <a href="{{ url('admin/riwayat/{user}/lihat') }}"
                    class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-arrow-long-left class="w-5" />
                    Kembali
                </a>
                </center>
            </div>
            <br>
            
            <!-- Informasi Waktu Pengerjaan -->
            <div class="p-4 mt-3">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-100 p-3 rounded-md">
                        <h4 class="font-semibold text-gray-700">Start Waktu Pengerjaan</h4>
                        <p>{{ $createdAt }}</p>
                    </div>
                    <div class="bg-gray-100 p-3 rounded-md">
                        <h4 class="font-semibold text-gray-700">Akhir Waktu Pengerjaan</h4>
                        <p>{{ $updatedAt }}</p>
                    </div>
                    <div class="col-span-2 bg-gray-100 p-3 rounded-md">
                        <h4 class="font-semibold text-gray-700">Waktu Pengerjaan</h4>
                        @php
                            $timeTaken = \Carbon\Carbon::parse($createdAt)->diff(\Carbon\Carbon::parse($updatedAt))->format('%h hours %i minutes');
                        @endphp
                        <p>{{ $timeTaken }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Jawaban dan Nilai -->
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Jawaban {{ $pp->judulPrePost }}</th>
                                <th class="px-4 py-2">Nilai Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalNilai = 0;
                            @endphp
                            @if ($pp->judulPrePost == 'Post Test' && $answerPostTest)
                                @foreach ($answerPostTest as $index => $answer)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">{{ $answer->typed_answer }}</td>
                                        <td class="border px-4 py-2">{{ $answer->nilai }}</td>
                                    </tr>
                                    @php
                                        $totalNilai += $answer->nilai;
                                    @endphp
                                @endforeach
                            @elseif ($pp->judulPrePost == 'Pre Test' && $answerPreTest)
                                @foreach ($answerPreTest as $index => $answer)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">{{ $answer->typed_answer }}</td>
                                        <td class="border px-4 py-2">{{ $answer->nilai }}</td>
                                    </tr>
                                    @php
                                        $totalNilai += $answer->nilai;
                                    @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekapan Nilai -->
            <div class="p-4 mt-3 bg-gray-200 rounded-md">
                <div class="flex justify-between items-center">
                    <h4 class="font-semibold text-gray-700">Total Nilai</h4>
                    <p class="font-bold text-xl text-gray-800">{{ $totalNilai }}</p>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="flex justify-end mt-5">
                <a href="{{ route('admin.riwayat.index') }}" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
