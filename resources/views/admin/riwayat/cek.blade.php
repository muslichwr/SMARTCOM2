@extends('layouts.admin')

@section('title', 'Progress User Page')

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
                            <h3 class="text-lg font-semibold">Daftar Materi {{ $user->name }}</h3>
                            <a href="{{ url('admin/riwayat') }}"
                                class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                                <x-heroicon-o-arrow-long-left class="w-5" />
                                Kembali
                            </a>
                        </center>
                    </div>
                    <br>
                    <div class="p-4 mt-3">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Judul Materi</th>
                                        <th class="px-4 py-2">Bab Materi</th>
                                        <th class="px-4 py-2">Latihan</th>
                                        <th class="px-4 py-2">Progress Belajar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materis as $materi)
                                        <tr class="text-center">
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ $materi->judul }}</td>
                                            <td class="border px-4 py-2">{{ $materi->babs_count }}</td>
                                            <td class="border px-4 py-2">{{ $materi->latihans_count }}</td>
                                            <td class="border px-4 py-2">
                                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                                    @php
                                                        // Hitung persentase progress
                                                        $progress = 0;
                                                        if ($materi->total > 0) {
                                                            $babsCount = isset($materi->babs_attempt_status) ? $materi->babs_attempt_status->count() : 0;
                                                            $latihansCount = isset($materi->latihans_attempt_status) ? $materi->latihans_attempt_status : 0; // Mengubah menjadi variabel integer
                                                            $progress = (($babsCount + $latihansCount) / $materi->total) * 100;

                                                            if ($progress >= 100) {
                                                                $progress = 100;
                                                            }

                                                            $progress = number_format($progress, 1);

                                                            // dd($progress);
                                                        }
                                                    @endphp

                                                    <div class="bg-blue-600 text-xs font-medium text-black-100 text-center p-0.5 leading-none rounded-full"
                                                        style="width: {{ min($progress, 100) }}%">
                                                        {{ $progress }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Pre Post Test --}}
                            <br>
                            <br>
                            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                                <center>
                                    <h3 class="text-lg font-semibold">Daftar Pre / Post Test {{ $user->name }}</h3>
                                </center>
                            </div>

                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Pre / Post Test</th>
                                        <th class="px-4 py-2">Sudah Dikerjakan?</th>
                                        <th class="px-4 py-2">Lihat Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($preposts as $prepost)
                                        <tr class="text-center">
                                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                            <td class="border px-4 py-2">{{ $prepost->judulPrePost }}</td>
                                            <td class="border px-4 py-2">
                                                @if ($prepost->judulPrePost == 'Pre Test')
                                                    @if ($statusPre && ($statusPre->status == 1 || $statusPre->status == 2))
                                                        <x-heroicon-o-check-circle class="text-green-500 w-6 h-6" />
                                                    @else
                                                        <x-heroicon-o-x-circle class="text-red-500 w-6 h-6" />
                                                    @endif
                                                @elseif ($prepost->judulPrePost == 'Post Test')
                                                    @if ($statusPost && ($statusPost->status == 1 || $statusPost->status == 2))
                                                        <x-heroicon-o-check-circle class="text-green-500 w-6 h-6" />
                                                    @else
                                                        <x-heroicon-o-x-circle class="text-red-500 w-6 h-6" />
                                                    @endif
                                                @else
                                                    <!-- Jika bukan Pre Test atau Post Test, biarkan kosong -->
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">
                                                <a href="{{ url('admin/riwayat/' . $user->id . '/lihat/' . $prepost->id) }}"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Periksa
                                                    Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="mt-4">
                            {{ $materis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection
