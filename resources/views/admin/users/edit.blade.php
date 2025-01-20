@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Edit Pengguna</h3>
                    <a href="{{ url('admin/users') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali
                    </a>
                </div>
                <div class="p-4">
                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="bg-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Form untuk mengedit pengguna --}}
                    <form action="{{ url('admin/users/' . $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <br>
                        <div class="tab-content mt-2" id="myTabContent">
                            <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="mb-4">
                                    <label class="block mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ $user->name }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('name')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-1">Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('email')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-1">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('password')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block mb-1">Role</label>
                                    <select name="role_as"
                                        class="border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                        <option value="0" {{ $user->role_as == 0 ? 'selected' : '' }}>User</option>
                                        <option value="1" {{ $user->role_as == 1 ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ $user->role_as == 2 ? 'selected' : '' }}>Guru</option>
                                    </select>
                                    @error('role_as')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="py-2 flex justify-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
