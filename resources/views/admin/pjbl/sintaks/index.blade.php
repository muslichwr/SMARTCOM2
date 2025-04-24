@extends('layouts.admin')

@section('title', 'Daftar Materi PJBL')

@section('content')
<div class="flex justify-center mt-5">
    <div class="w-full mt-3">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg mt-3">
            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                <h3 class="text-lg font-semibold">Manajemen PJBL Sintaks</h3>
            </div>
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Judul Materi</th>
                                <th class="px-4 py-2">Deskripsi</th>
                                <th class="px-4 py-2">Status PJBL</th>
                                <th class="px-4 py-2">Jumlah Kelompok</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materi as $index => $item)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $item->judul }}</td>
                                    <td class="border px-4 py-2">{{ \Illuminate\Support\Str::limit($item->deskripsi, 100) }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="px-2 py-1 rounded text-white {{ $item->pjbl_sintaks_active ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $item->pjbl_sintaks_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($item->pjbl_sintaks_active)
                                            {{ $item->kelompok_count ?? 0 }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('admin.pjbl.sintaks.toggle', $item->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-sm rounded my-1 inline-block text-white {{ $item->pjbl_sintaks_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
                                                {{ $item->pjbl_sintaks_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        
                                        @if($item->pjbl_sintaks_active)
                                            <a href="{{ route('admin.pjbl.sintaks.kelompok', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">
                                                Lihat Kelompok
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="6">Belum ada data materi</td>
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