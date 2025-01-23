@extends('layouts.admin')

@section('title', 'Daftar Materi PJBL')

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
                <h3 class="text-lg font-semibold">Daftar Materi PJBL</h3>
            </div>
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Judul Materi</th>
                                <th class="px-4 py-2">Jumlah Kelompok</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materi as $index => $item)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $item->judul }}</td>
                                    <td class="border px-4 py-2">{{ $item->jumlah_kelompok }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('admin.pjbl.sintaks.kelompok', $item) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">
                                            Lihat Kelompok
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="4">Belum Ada Materi yang Ditambahkan.</td>
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