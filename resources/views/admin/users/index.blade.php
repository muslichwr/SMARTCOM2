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
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Daftar Pengguna</h3>
                    <div class="flex gap-2"> <!-- Flex container for buttons -->
                        <a href="{{ url('admin/users/create') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded">
                            <x-heroicon-o-plus-circle class="w-5" />
                            Tambah Pengguna
                        </a>
                        <a href="{{ url('admin/users/bulk-create') }}" class="flex gap-1 items-center bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                            <x-heroicon-m-variable class="w-5" />
                            Upload CSV
                        </a>
                    </div>
                </div>
                <br>
                <div class="p-4 mt-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Nama</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2">Role</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $users->firstItem() + $index }}</td>
                                        <td class="border px-4 py-2">{{ $user->name }}</td>
                                        <td class="border px-4 py-2">{{ $user->email }}</td>
                                        <td class="border px-4 py-2">
                                            @if ($user->role_as == 0)
                                                User
                                            @elseif ($user->role_as == 1)
                                                Admin
                                            @else
                                                Guru
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a href="{{ url('admin/users/' . $user->id . '/edit') }}"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                            <form action="{{ url('admin/users/' . $user->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-2 text-center">Belum Ada Pengguna yang Ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
