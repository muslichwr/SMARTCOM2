@extends('layouts.admin')

@section('title', 'Daftar Kelompok PJBL')

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
                <h3 class="text-lg font-semibold">Daftar Kelompok PJBL - {{ $materi->judul }}</h3>
                <a href="{{ route('admin.pjbl.sintaks.index') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-arrow-left class="w-5" />
                    Kembali ke Daftar Materi
                </a>
            </div>
            <br>
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama Kelompok</th>
                                <th class="px-4 py-2">Jumlah Anggota</th>
                                <th class="px-4 py-2">Ketua</th>
                                <th class="px-4 py-2">Anggota</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelompok as $index => $kel)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $kel->kelompok }}</td>
                                    <td class="border px-4 py-2">{{ $kel->anggotas->count() }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($kel->ketua)
                                            {{ $kel->ketua->user->name }}
                                        @else
                                            Belum Ada Ketua
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <ul class="list-disc list-inside text-left">
                                            @foreach ($kel->anggotas as $anggota)
                                                <li>{{ $anggota->user->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('admin.pjbl.sintaks.detail', [$materi, $kel]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="6">Belum Ada Kelompok yang Ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection