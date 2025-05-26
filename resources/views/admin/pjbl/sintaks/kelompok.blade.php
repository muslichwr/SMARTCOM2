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
                <div class="flex gap-3 mt-5">
                    <a href="{{ route('admin.pjbl.sintaks.index') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded">
                        <x-heroicon-o-arrow-left class="w-5" />
                        Kembali ke Daftar Materi
                    </a>
                    <button type="button" onclick="document.getElementById('postOrientasiModal').classList.remove('hidden')" class="flex gap-1 items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                        <x-heroicon-o-plus class="w-5" />
                        Posting Orientasi Masalah
                    </button>
                </div>
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

<!-- Modal untuk posting orientasi masalah -->
<div id="postOrientasiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex flex-col">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold">Posting Orientasi Masalah ke Seluruh Kelompok</h3>
                <button type="button" onclick="document.getElementById('postOrientasiModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                    <x-heroicon-o-x-mark class="w-6 h-6" />
                </button>
            </div>
            
            <form action="{{ route('admin.pjbl.sintaks.post-orientasi', $materi) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="orientasi_masalah" class="block text-gray-700 text-sm font-bold mb-2">Orientasi Masalah:</label>
                    <textarea id="orientasi_masalah" name="orientasi_masalah" rows="6" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="button" onclick="document.getElementById('postOrientasiModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        Posting ke Semua Kelompok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection