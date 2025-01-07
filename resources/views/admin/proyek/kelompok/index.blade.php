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
                    <h3 class="text-lg font-semibold">Kelompok</h3>
                    <button
                        class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5"
                        data-modal-target="static-modal" data-modal-toggle="static-modal" type="button">
                        <x-heroicon-o-plus class="mr-2 w-5" />
                        Kelompok
                    </button>
                </div>
                <br>
                <div class="p-4 mt-3">
                    <div class="fixed left-0 right-0 top-0 z-50 hidden h-screen w-full items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-800/50"
                        id="static-modal" aria-hidden="true" tabindex="-1">
                        <div class="relative max-h-full w-full max-w-4xl p-4">
                            <!-- Modal Content -->
                            <div class="relative rounded-md border border-gray-200 bg-white shadow">
                                <!-- Modal Header -->
                                <div class="flex items-center justify-between rounded-t bg-primary-100/25 p-4">
                                    <h3 class="font-head text-lg font-semibold text-primary-400 md:text-xl">
                                        Tambah Kelompok
                                    </h3>
                                    <button
                                        class="flex w-max flex-row items-center justify-between rounded bg-red-400 p-2 font-head text-sm font-semibold text-white transition duration-200 hover:bg-red-600 md:text-base"
                                        data-modal-hide="static-modal" type="button">
                                        <x-heroicon-o-x-mark class="w-5" />
                                        <span class="sr-only">Close Modal</span>
                                    </button>
                                </div>
                                <!-- Modal Body -->
                                <div class="p-4">
                                    <form action="{{ url('admin/proyek/kelompok') }}" method="POST">
                                        @csrf
                                        <div class="">
                                            <x-input-label for="kelompok" :value="__('Nama Kelompok')" />
                                            <x-text-input class="my-2 block w-full" id="kelompok" name="kelompok"
                                                type="text" required autofocus />
                                            @error('kelompok')
                                                <small class="text-red-400">
                                                    {{ $message }}
                                                </small>
                                            @enderror
                                        </div>
                                        <div class="flex justify-end pt-2">
                                            <button class="success-button" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Nama Kelompok</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelompoks as $index => $kelompok)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $kelompoks->firstItem() + $index }}</td>
                                        <td class="border px-4 py-2">{{ $kelompok->kelompok }}</td>
                                        <td class="border px-4 py-2">
                                            <button
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block"
                                                data-modal-target="static-modal-edit-{{ $kelompok->id }}"
                                                data-modal-toggle="static-modal-edit-{{ $kelompok->id }}" type="button">
                                                Ubah
                                            </button>
                                            <div class="fixed -left-4 top-0 z-50 hidden h-[calc(100%+3rem)] w-screen items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-800/50"
                                                id="static-modal-edit-{{ $kelompok->id }}" aria-hidden="true"
                                                tabindex="-1">
                                                <div class="relative max-h-full w-full max-w-4xl p-4">
                                                    <!-- Modal Content -->
                                                    <div class="relative rounded-md border border-gray-200 bg-white shadow">
                                                        <!-- Modal Header -->
                                                        <div
                                                            class="flex items-center justify-between rounded-t bg-primary-100/25 p-4">
                                                            <h3
                                                                class="font-head text-lg font-semibold text-primary-400 md:text-xl">
                                                                Perbarui Kelompok
                                                            </h3>
                                                            <button
                                                                class="flex w-max flex-row items-center justify-between rounded bg-red-400 p-2 font-head text-sm font-semibold text-white transition duration-200 hover:bg-red-600 md:text-base"
                                                                data-modal-hide="static-modal-edit-{{ $kelompok->id }}"
                                                                type="button">
                                                                <x-heroicon-o-x-mark class="w-5" />
                                                                <span class="sr-only">Close Modal</span>
                                                            </button>
                                                        </div>
                                                        <!-- Modal Body -->
                                                        <div class="p-4">
                                                            <form
                                                                action="{{ url('admin/proyek/kelompok/' . $kelompok->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="text-start">
                                                                    <x-input-label for="kelompok" :value="__('Nama Kelompok')" />
                                                                    <x-text-input class="my-2 block w-full" id="kelompok"
                                                                        name="kelompok" type="text"
                                                                        value="{{ $kelompok->kelompok }}" required
                                                                        autofocus />
                                                                    @error('kelompok')
                                                                        <small class="text-red-400">
                                                                            {{ $message }}
                                                                        </small>
                                                                    @enderror
                                                                </div>
                                                                <div class="flex justify-end pt-2">
                                                                    <button class="success-button"
                                                                        type="submit">Perbarui</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block"
                                                data-modal-target="static-modal-delete-{{ $kelompok->id }}"
                                                data-modal-toggle="static-modal-delete-{{ $kelompok->id }}" type="button">
                                                Hapus
                                            </button>
                                            <div class="fixed -left-4 top-0 z-50 hidden h-[calc(100%+3rem)] w-screen items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-800/50"
                                                id="static-modal-delete-{{ $kelompok->id }}" aria-hidden="true"
                                                tabindex="-1">
                                                <div class="relative max-h-full w-full max-w-4xl p-4">
                                                    <!-- Modal Content -->
                                                    <div class="relative rounded-md border border-gray-200 bg-white shadow">
                                                        <!-- Modal Header -->
                                                        <div
                                                            class="flex items-center justify-between rounded-t bg-primary-100/25 p-4">
                                                            <h3
                                                                class="font-head text-lg font-semibold text-primary-400 md:text-xl">
                                                                Hapus Kelompok
                                                            </h3>
                                                            <button
                                                                class="flex w-max flex-row items-center justify-between rounded bg-red-400 p-2 font-head text-sm font-semibold text-white transition duration-200 hover:bg-red-600 md:text-base"
                                                                data-modal-hide="static-modal-delete-{{ $kelompok->id }}"
                                                                type="button">
                                                                <x-heroicon-o-x-mark class="w-5" />
                                                                <span class="sr-only">Close Modal</span>
                                                            </button>
                                                        </div>
                                                        <!-- Modal Body -->
                                                        <div class="p-4">
                                                            <form action="{{ url('admin/proyek/kelompok/' . $kelompok->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('delete')
                                                                <div class="text-center font-head text-lg font-semibold">
                                                                    <p>
                                                                        Anda Akan Menghapus Kelompok
                                                                        <span class="text-red-600">
                                                                            {{ $kelompok->kelompok }}
                                                                        </span>
                                                                        ?
                                                                    </p>
                                                                </div>
                                                                <div class="flex justify-end pt-2">
                                                                    <button class="dangers-button"
                                                                        type="submit">Hapus</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $kelompoks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
