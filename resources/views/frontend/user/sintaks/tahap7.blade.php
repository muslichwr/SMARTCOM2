@extends('layouts.appF')

@section('title', 'Tahap 7: Penilaian dan Evaluasi Proyek')

@section('content')
    <div class="bg-white min-h-screen flex items-center justify-center py-10">
        <div class="w-full max-w-3xl mt-5">
            <div class="bg-white shadow-lg rounded-lg mt-3">
                <div class="bg-gray-200 px-6 py-4 rounded-t-lg">
                    <center>
                        <h3 class="text-xl font-semibold text-gray-800">Tahap 7: Penilaian dan Evaluasi Proyek</h3>
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

                    <!-- Cek apakah penilaian sudah dilakukan -->
                    @if ($tahapTuju && $tahapTuju->status_validasi == 'valid')
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="font-semibold">Selesai</span>
                            <p class="mt-2"><strong>Feedback Guru:</strong> {{ $tahapTuju->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Tampilkan nilai individu -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold mb-3">Nilai Individu</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="py-2 px-3 border-b text-left text-sm font-medium text-gray-700">Anggota</th>
                                            <th class="py-2 px-3 border-b text-left text-sm font-medium text-gray-700">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalNilai = 0; $jumlahNilai = 0; @endphp
                                        @foreach($tahapTuju->nilaiIndividu as $nilai)
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-2 px-3 border-b">{{ $nilai->user->name }}</td>
                                                <td class="py-2 px-3 border-b">
                                                    <span class="font-semibold">{{ $nilai->total_nilai_individu }}</span>
                                                    @php 
                                                        $totalNilai += $nilai->total_nilai_individu; 
                                                        $jumlahNilai++; 
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tampilkan total nilai -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Total Nilai Kelompok</h4>
                            <p class="text-4xl font-bold text-blue-800">
                                {{ $jumlahNilai > 0 ? round($totalNilai / $jumlahNilai, 1) : 0 }}
                            </p>
                            <p class="text-sm text-gray-600 mt-2">Dari total maksimal 100</p>
                        </div>
                    @elseif ($tahapTuju && $tahapTuju->status_validasi == 'pending')
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="font-semibold">Menunggu Penilaian</span>
                            <p class="mt-2">Silakan menunggu guru untuk memberikan penilaian.</p>
                        </div>
                    @else
                        <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="font-semibold">Belum Diminta</span>
                            <p class="mt-2">Silakan klik tombol di bawah untuk meminta penilaian dari guru.</p>
                        </div>
                    @endif

                    <!-- Tombol Minta Penilaian -->
                    @if (!$tahapTuju || $tahapTuju->status_validasi != 'valid')
                        <form action="{{ route('user.materi.tahap7', ['slug' => $materi->slug]) }}" method="POST">
                            @csrf
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md transition duration-300">
                                    Minta Penilaian
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection