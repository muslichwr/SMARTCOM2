@extends('layouts.appF')

@section('title', 'Tahap 6: Pengumpulan Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 6: Pengumpulan Proyek</h3>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Daftar Sintaks
                    </a>
                </div>

                <div class="p-6">
                    <!-- Menampilkan pesan sukses atau error -->
                    @if (session('success'))
                        <div class="bg-green-200 p-4 rounded-lg mb-4">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-200 p-4 rounded-lg mb-4">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif

                    <!-- Form Upload File Proyek dan Laporan -->
                    <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap6') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file_proyek" class="block text-sm font-semibold">Upload File Proyek</label>
                            <input type="file" name="file_proyek" id="file_proyek" class="border border-gray-300 p-2 w-full" required />
                        </div>
                        <div class="mb-4">
                            <label for="file_laporan" class="block text-sm font-semibold">Upload File Laporan</label>
                            <input type="file" name="file_laporan" id="file_laporan" class="border border-gray-300 p-2 w-full" required />
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                            Upload Proyek dan Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
