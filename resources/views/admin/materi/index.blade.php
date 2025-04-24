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
                    <h3 class="text-lg font-semibold">Materi</h3>
                    <a href="{{ url('admin/materi/create') }}"
                        class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-plus-circle class="w-5" />
                        Tambah Materi
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
                                    <th class="px-4 py-2">Bab Materi</th>
                                    <th class="px-4 py-2">Status PJBL</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materis as $index => $materi)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $materis->firstItem() + $index }}</td>
                                        <td class="border px-4 py-2">{{ $materi->judul }}</td>
                                        <td class="border px-4 py-2">{{ $materi->babs_count }}</td>
                                        <td class="border px-4 py-2">
                                            <span class="px-2 py-1 rounded text-white {{ $materi->pjbl_sintaks_active ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ $materi->pjbl_sintaks_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ url('admin/materi/' . $materi->id . '/edit') }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                            <a href="{{ url('admin/materi/'. $materi->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block" data-confirm-delete="true">Delete</a>
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
