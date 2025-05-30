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
                <h3 class="text-lg font-semibold">Edit Kelompok PJBL: {{ $kelompok->kelompok }}</h3>
            </div>
            <div class="p-4 mt-3">
                <form action="{{ route('kelompok.update', $kelompok->slug) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <!-- Nama Kelompok -->
                        <div class="form-group">
                            <label for="kelompok" class="block text-sm font-medium text-gray-700">Nama Kelompok</label>
                            <input type="text" name="kelompok" id="kelompok" value="{{ old('kelompok', $kelompok->kelompok) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama kelompok" required>
                            @error('kelompok')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tampilkan Ketua yang Sudah Terdaftar -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700">Ketua Kelompok yang Terdaftar</label>
                            <p class="text-sm font-semibold">
                                @if ($ketua)
                                    {{ $ketua->user->name }}
                                @else
                                    Belum ada ketua
                                @endif
                            </p>
                        </div>

                        <!-- Pilih Ketua -->
                        <div class="form-group">
                            <label for="ketua" class="block text-sm font-medium text-gray-700">Pilih Ketua Kelompok</label>
                            <select name="ketua_id" id="ketua" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Ketua</option>
                                @foreach ($siswa as $user)
                                    <option value="{{ $user->id }}" 
                                        @if ($user->id == $kelompok->ketua_id) selected @endif>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ketua_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tampilkan Anggota yang Sudah Terdaftar -->
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700">Anggota Kelompok yang Terdaftar</label>
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($anggotas as $anggota)
                                    <li>{{ $anggota->user->name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Pilih Anggota -->
                        <div class="form-group">
                            <label for="anggota" class="block text-sm font-medium text-gray-700">Pilih Anggota Kelompok</label>
                            @if ($siswa->isEmpty())
                                <p class="text-red-500 text-sm">Tidak ada siswa yang tersedia untuk dipilih.</p>
                            @else
                                <select name="anggotas[]" id="anggota" multiple
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @foreach ($siswa as $user)
                                        <option value="{{ $user->id }}" 
                                            @if (in_array($user->id, old('anggotas', $kelompokAnggota))) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <small class="text-gray-500">Ctrl+klik untuk memilih banyak anggota.</small>
                            @error('anggotas')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end mt-4 space-x-4">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('kelompok.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                Kembali
                            </a>
                            <!-- Tombol Simpan Perubahan -->
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
