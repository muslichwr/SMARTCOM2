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
                <h3 class="text-lg font-semibold">Daftar Bab</h3>
                <a href="{{ url('admin/bab/create') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-plus-circle class="w-5" />
                    Tambah Bab Materi
                </a>
            </div>
            <br>
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Materi</th>
                                <th class="px-4 py-2">Judul Bab</th>
                                <th class="px-4 py-2">File</th>
                                <th class="px-4 py-2">Video URL</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($babs as $index => $bab)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $babs->firstItem() + $index }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($bab->materi)
                                            {{ $bab->materi->judul }}
                                        @else
                                            Tidak Ada Materi yang Sesuai
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $bab->judul }}</td>
                                    <td class="border px-4 py-2">
                                        @if($bab->file_path)
                                            <a href="{{ Storage::url($bab->file_path) }}" target="_blank">View File</a>
                                        @else
                                            No File
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($bab->video_url)
                                            <a href="{{ $bab->video_url }}" target="_blank">View Video</a>
                                        @else
                                            No Video
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ url('admin/bab/' . $bab->id . '/edit') }}"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                        <form action="{{ url('admin/bab/' . $bab->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block confirm-delete">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="6">Belum Ada Bab yang Ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $babs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
