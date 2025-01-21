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
                <h3 class="text-lg font-semibold">Tambah Kelompok PJBL</h3>
            </div>
            <div class="p-4 mt-3">
                <form action="{{ route('kelompok.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">

                        <!-- Nama Kelompok -->
                        <div class="form-group">
                            <label for="kelompok" class="block text-sm font-medium text-gray-600">Nama Kelompok</label>
                            <input type="text" name="kelompok" id="kelompok" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                value="{{ old('kelompok') }}" required>
                            @error('kelompok')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemilihan Anggota -->
                        <div class="form-group">
                            <label for="anggotas" class="block text-sm font-medium text-gray-600">Pilih Anggota</label>
                            <select name="anggotas[]" id="anggotas" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                multiple required>
                                @foreach ($siswa as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('anggotas', [])) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-gray-500">Ctrl + Klik untuk memilih banyak anggota</small>
                            @error('anggotas')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pemilihan Ketua -->
                        <div class="form-group">
                            <label for="ketua_id" class="block text-sm font-medium text-gray-600">Pilih Ketua</label>
                            <select name="ketua_id" id="ketua_id" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                required>
                                <option value="">Pilih Ketua</option>
                                @foreach ($siswa as $user)
                                    <option value="{{ $user->id }}" {{ old('ketua_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ketua_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end mt-6 space-x-4">
                            <!-- Tombol Kembali -->
                            <a href="{{ route('kelompok.index') }}" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md">
                                Kembali
                            </a>
                            <!-- Tombol Simpan -->
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md">
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
