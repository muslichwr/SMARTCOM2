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
                    <h3 class="text-lg font-semibold">Soal & Jawaban</h3>
                    <a href="{{ url('admin/soal-jawaban/create') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-plus-circle class="w-5" />
                        Tambah Soal & Jawaban
                    </a>
                </div>
                <br>
                <div class="p-4 mt-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Pertanyaan</th>
                                    <th class="px-4 py-2">Jawaban</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($soals as $index => $soal)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $soals->firstItem() + $index }}</td>
                                        <td class="border px-4 py-2">{{ $soal->question }}</td>
                                        <td class="border px-4 py-2">
                                            @if ($soal->jawaban)
                                                {{ $soal->jawaban->answer }}
                                            @else
                                                Tidak Ada Jawaban yang Sesuai dengan Soal.
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ url('admin/soal-jawaban/' . $soal->id . '/edit') }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                            <a href="{{ url('admin/soal-jawaban/' . $soal->id) }}"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block"
                                                data-confirm-delete="true">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="border px-4 py-2">Belum Ada Latihan yang Ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $soals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
