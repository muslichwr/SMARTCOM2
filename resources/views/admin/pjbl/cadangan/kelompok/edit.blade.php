@extends('layouts.admin')

@section('content')
<div class="flex justify-center mt-5">
    <div class="w-full mt-3">
        <div class="bg-white shadow-md rounded-lg">
            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                <h3 class="text-lg font-semibold">Edit Kelompok</h3>
                <a href="{{ url('admin/pjbl/kelompok') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-arrow-long-left class="w-5" />
                    Kembali
                </a>
            </div>
            <div class="p-4">
                {{-- Menampilkan error validation jika ada --}}
                @if ($errors->any())
                    <div class="bg-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ url('admin/pjbl/kelompok/' . $kelompok->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block mb-1">Nama Kelompok</label>
                        <input type="text" name="kelompok" value="{{ old('kelompok', $kelompok->kelompok) }}"
                            class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                        @error('kelompok')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $kelompok->slug) }}"
                            class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                        @error('slug')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Anggota Kelompok</label>
                        <select name="anggota[]" multiple
                            class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, $kelompok->anggota->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">Ketua Kelompok</label>
                        <select name="ketua_id"
                            class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $user->id == $kelompok->ketua_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ketua_id')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="py-2 flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
