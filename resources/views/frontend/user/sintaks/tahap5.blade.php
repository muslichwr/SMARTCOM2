@extends('layouts.appF')

@section('title', 'Tahap 5: Hasil Karya Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 5: Hasil Karya Proyek</h3>
                    </center>
                    <a href="{{ url('user/materi/' . $materi->slug . '/sintaks') }}"
                        class="flex items-center float-right gap-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 text-sm rounded mt-5 w-max">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali ke Daftar Sintaks
                    </a>
                </div>
                <br>
                <div class="p-6">
                    <!-- Menampilkan pesan sukses atau error -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif

                    <!-- Cek apakah tahap sudah divalidasi -->
                    @if ($tahapLima && $tahapLima->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Valid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapLima->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @elseif ($tahapLima && $tahapLima->status_validasi == 'invalid')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Validasi:</strong> <span class="font-semibold">Invalid</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapLima->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @endif

                    <!-- Form untuk mengunggah hasil karya jika belum divalidasi -->
                    @if (!$tahapLima || $tahapLima->status_validasi != 'valid')
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap5') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div class="mb-4">
                                    <label for="deskripsi_hasil" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Hasil Karya</label>
                                    <textarea name="deskripsi_hasil" id="deskripsi_hasil" rows="6" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    placeholder="Jelaskan tentang hasil karya proyek yang telah dibuat...">{{ $tahapLima->deskripsi_hasil ?? '' }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="file_hasil_karya" class="block text-sm font-medium text-gray-700 mb-1">Upload File Hasil Karya</label>
                                    <input type="file" name="file_hasil_karya" id="file_hasil_karya" 
                                    class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100">
                                    <p class="mt-1 text-sm text-gray-500">Upload file dengan format PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, atau ZIP (maks. 20MB)</p>
                                </div>

                                @if($tahapLima && $tahapLima->file_hasil_karya)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-1">File Hasil Karya yang Sudah Diupload:</p>
                                    <div class="flex items-center mt-2">
                                        <a href="{{ asset('storage/' . $tahapLima->file_hasil_karya) }}" target="_blank" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                        <a href="{{ asset('storage/' . $tahapLima->file_hasil_karya) }}" target="_blank" 
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-md text-sm">
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                        Simpan Hasil Karya
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <!-- Tampilkan data yang sudah divalidasi -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-lg font-semibold mb-2">Deskripsi Hasil Karya</h4>
                                <p class="text-gray-700">{{ $tahapLima->deskripsi_hasil }}</p>
                            </div>

                            @if($tahapLima->file_hasil_karya)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-lg font-semibold mb-2">File Hasil Karya</h4>
                                <div class="flex items-center mt-2">
                                    <a href="{{ asset('storage/' . $tahapLima->file_hasil_karya) }}" target="_blank" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm mr-2">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                    <a href="{{ asset('storage/' . $tahapLima->file_hasil_karya) }}" target="_blank" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-md text-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Tombol sudah divalidasi -->
                        <div class="mt-6 flex justify-end">
                            <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-lg cursor-not-allowed opacity-50" disabled>
                                Sudah Divalidasi
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection