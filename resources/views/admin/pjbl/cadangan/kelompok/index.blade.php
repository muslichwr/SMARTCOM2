@extends('layouts.admin')

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
                <h3 class="text-lg font-semibold">Daftar Kelompok</h3>
                <a href="{{ url('admin/pjbl/kelompok/create') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-plus-circle class="w-5" />
                    Tambah Kelompok
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
                                <th class="px-4 py-2">Anggota</th>
                                <th class="px-4 py-2">Ketua</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelompoks as $index => $kelompok)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $kelompoks->firstItem() + $index }}</td>
                                    <td class="border px-4 py-2">{{ $kelompok->kelompok }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($kelompok->anggotas && $kelompok->anggotas->count() > 0)
                                            @foreach ($kelompok->anggotas as $anggota)
                                                {{ $anggota->user->name }}<br>
                                            @endforeach
                                        @else
                                            Tidak Ada Anggota
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if ($kelompok->ketua)
                                            {{ $kelompok->ketua->user->name }}
                                        @else
                                            Belum Ditunjuk
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ url('admin/pjbl/kelompok/' . $kelompok->id . '/edit') }}"
                                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                        <form action="{{ url('admin/pjbl/kelompok/' . $kelompok->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button data-confirm-delete="true" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kelompok ini?')">Hapus</button>
                                        </form>
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
                <div class="mt-4">
                    {{ $kelompoks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
