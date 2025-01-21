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
                <h3 class="text-lg font-semibold">Detail Kelompok PJBL: {{ $kelompok->kelompok }}</h3>
            </div>
            <div class="p-4 mt-3">
                <div class="space-y-4">
                    <!-- Nama Kelompok -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                        <p class="text-sm font-semibold">{{ $kelompok->kelompok }}</p>
                    </div>

                    <!-- Tampilkan Ketua -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700">Ketua Kelompok</label>
                        <p class="text-sm font-semibold">
                            @if ($ketua)
                                {{ $ketua->user->name }}
                            @else
                                Belum ada ketua
                            @endif
                        </p>
                    </div>

                    <!-- Tampilkan Anggota yang Terdaftar -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700">Anggota Kelompok</label>
                        @if ($anggotas->isEmpty())
                            <p class="text-sm text-red-500">Tidak ada anggota yang terdaftar.</p>
                        @else
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($anggotas as $anggota)
                                    <li>{{ $anggota->user->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Tombol Kembali -->
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('kelompok.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
