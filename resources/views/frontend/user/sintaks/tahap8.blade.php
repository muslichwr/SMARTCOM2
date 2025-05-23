@extends('layouts.appF')

@section('title', 'Tahap 8: Evaluasi dan Refleksi')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 8: Evaluasi dan Refleksi</h3>
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

                    <!-- Cek status validasi -->
                    @if ($tahapDelapan && $tahapDelapan->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Evaluasi:</strong> <span class="font-semibold">Selesai</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapDelapan->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @elseif ($tahapDelapan && $tahapDelapan->status_validasi == 'invalid')
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Evaluasi:</strong> <span class="font-semibold">Perlu Perbaikan</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapDelapan->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>
                    @elseif ($tahapDelapan && $tahapDelapan->status_validasi == 'pending')
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Evaluasi:</strong> <span class="font-semibold">Menunggu Validasi</span>
                            <p class="mt-2">Silakan menunggu guru untuk memvalidasi evaluasi dan refleksi.</p>
                        </div>
                    @else
                        <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Evaluasi:</strong> <span class="font-semibold">Belum Mulai</span>
                            <p class="mt-2">Silakan mulai mengerjakan evaluasi dan refleksi.</p>
                        </div>
                    @endif

                    <!-- Form Evaluasi Kelompok -->
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-3">Evaluasi Kelompok</h4>
                        <form action="{{ route('user.materi.simpan-tahap8', ['slug' => $materi->slug]) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="evaluasi_kelompok" class="block text-sm font-medium text-gray-700 mb-1">Evaluasi Kelompok</label>
                                <textarea name="evaluasi_kelompok" id="evaluasi_kelompok" rows="4" 
                                    class="w-full border border-gray-300 rounded-lg p-2 @error('evaluasi_kelompok') border-red-500 @enderror"
                                    {{ $tahapDelapan && $tahapDelapan->status_validasi == 'valid' ? 'disabled' : '' }}>{{ $tahapDelapan->evaluasi_kelompok ?? old('evaluasi_kelompok') }}</textarea>
                                @error('evaluasi_kelompok')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="refleksi_pembelajaran" class="block text-sm font-medium text-gray-700 mb-1">Refleksi Pembelajaran</label>
                                <textarea name="refleksi_pembelajaran" id="refleksi_pembelajaran" rows="4" 
                                    class="w-full border border-gray-300 rounded-lg p-2 @error('refleksi_pembelajaran') border-red-500 @enderror"
                                    {{ $tahapDelapan && $tahapDelapan->status_validasi == 'valid' ? 'disabled' : '' }}>{{ $tahapDelapan->refleksi_pembelajaran ?? old('refleksi_pembelajaran') }}</textarea>
                                @error('refleksi_pembelajaran')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @if ($tahapDelapan && $tahapDelapan->status_validasi != 'valid')
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300">
                                        Simpan Evaluasi Kelompok
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Refleksi Individu -->
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-3">Refleksi Individu</h4>
                        
                        @if ($refleksiSaya)
                            <form action="{{ route('user.materi.simpan-refleksi-individu', ['slug' => $materi->slug]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="refleksi_id" value="{{ $refleksiSaya->id }}">
                                
                                <div class="mb-4">
                                    <label for="kendala_dihadapi" class="block text-sm font-medium text-gray-700 mb-1">Kendala yang Dihadapi</label>
                                    <textarea name="kendala_dihadapi" id="kendala_dihadapi" rows="3" 
                                        class="w-full border border-gray-300 rounded-lg p-2 @error('kendala_dihadapi') border-red-500 @enderror"
                                        {{ $tahapDelapan && $tahapDelapan->status_validasi == 'valid' ? 'disabled' : '' }}>{{ $refleksiSaya->kendala_dihadapi ?? old('kendala_dihadapi') }}</textarea>
                                    @error('kendala_dihadapi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="pembelajaran_didapat" class="block text-sm font-medium text-gray-700 mb-1">Pembelajaran yang Didapat</label>
                                    <textarea name="pembelajaran_didapat" id="pembelajaran_didapat" rows="3" 
                                        class="w-full border border-gray-300 rounded-lg p-2 @error('pembelajaran_didapat') border-red-500 @enderror"
                                        {{ $tahapDelapan && $tahapDelapan->status_validasi == 'valid' ? 'disabled' : '' }}>{{ $refleksiSaya->pembelajaran_didapat ?? old('pembelajaran_didapat') }}</textarea>
                                    @error('pembelajaran_didapat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="refleksi_pribadi" class="block text-sm font-medium text-gray-700 mb-1">Refleksi Pribadi</label>
                                    <textarea name="refleksi_pribadi" id="refleksi_pribadi" rows="3" 
                                        class="w-full border border-gray-300 rounded-lg p-2 @error('refleksi_pribadi') border-red-500 @enderror"
                                        {{ $tahapDelapan && $tahapDelapan->status_validasi == 'valid' ? 'disabled' : '' }}>{{ $refleksiSaya->refleksi_pribadi ?? old('refleksi_pribadi') }}</textarea>
                                    @error('refleksi_pribadi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                @if ($tahapDelapan && $tahapDelapan->status_validasi != 'valid')
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300">
                                            Simpan Refleksi Pribadi
                                        </button>
                                    </div>
                                @endif
                            </form>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg">
                                <p>Refleksi individu belum tersedia. Silakan hubungi administrator.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Daftar Refleksi Anggota Kelompok (Jika sudah valid) -->
                    @if ($tahapDelapan && $tahapDelapan->status_validasi == 'valid')
                        <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-semibold mb-3">Refleksi Anggota Kelompok</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="py-2 px-3 border-b text-left text-sm font-medium text-gray-700">Anggota</th>
                                            <th class="py-2 px-3 border-b text-left text-sm font-medium text-gray-700">Kendala</th>
                                            <th class="py-2 px-3 border-b text-left text-sm font-medium text-gray-700">Pembelajaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tahapDelapan->refleksiIndividu as $refleksi)
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-2 px-3 border-b">{{ $refleksi->user->name }}</td>
                                                <td class="py-2 px-3 border-b">{{ Str::limit($refleksi->kendala_dihadapi, 50) }}</td>
                                                <td class="py-2 px-3 border-b">{{ Str::limit($refleksi->pembelajaran_didapat, 50) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection