@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Tambah Pengguna Serentak</h3>
                    <a href="{{ url('admin/users') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali
                    </a>
                </div>
                <div class="p-4">
                    {{-- Error messages --}}
                    @if ($errors->any())
                        <div class="bg-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tombol untuk mengunduh template CSV --}}
                    <div class="mb-4">
                        <a href="{{ route('admin.users.downloadTemplate') }}" class="inline-flex gap-1 items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                            <x-heroicon-o-document-arrow-down class="w-5" />
                            Unduh Template CSV
                        </a>
                    </div>

                    {{-- Upload Form --}}
                    <form action="{{ url('admin/users/bulk-create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="csv_file" class="block mb-1">Upload File CSV</label>
                            <input type="file" name="csv_file" class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" accept=".csv" required>
                        </div>

                        <div class="py-2 flex justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
