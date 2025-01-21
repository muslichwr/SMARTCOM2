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
                <h3 class="text-lg font-semibold">Buat Kelompok PJBL</h3>
            </div>
            <div class="p-4 mt-3">
                <form action="{{ route('kelompok.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <!-- Nama Kelompok -->
                        <div class="form-group">
                            <label for="kelompok" class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                            <input type="text" name="kelompok" id="kelompok" value="{{ old('kelompok') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama kelompok" required>
                            @error('kelompok')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pilih Ketua -->
                        <div class="form-group">
                            <label for="ketua" class="block text-sm font-medium text-gray-700">Pilih Ketua Kelompok</label>
                            <select name="ketua_id" id="ketua" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Ketua</option>
                                @foreach ($siswa as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('ketua_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Pilih Anggota -->
                        <div class="form-group">
                            <label for="anggota" class="block text-sm font-medium text-gray-700">Pilih Anggota Kelompok</label>
                            @if ($siswa->isEmpty())
                                <p class="text-red-500 text-sm">Tidak ada siswa yang tersedia untuk dipilih.</p>
                            @else
                                <select name="anggota[]" id="anggota" multiple
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($siswa as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <small class="text-gray-500">Ctrl+klik untuk memilih banyak anggota.</small>
                            @error('anggota')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end mt-4 space-x-4">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('kelompok.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                Kembali
                            </a>
                            <!-- Tombol Simpan -->
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Simpan Kelompok
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
