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

                    <!-- Cek apakah penilaian sudah dilakukan -->
                    @if ($sintaks && $sintaks->status_validasi == 'valid')
                        <div class="bg-green-100 p-4 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="text-green-600">Selesai</span>
                            <p><strong>Feedback Guru:</strong> {{ $sintaks->feedback_guru ?? 'Belum ada feedback' }}</p>
                        </div>

                        <!-- Tampilkan total nilai -->
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold mb-2">Total Nilai</h4>
                            <div class="space-y-2">
                                <p>Class dan Object: {{ $sintaks->score_class_object ?? '0' }}</p>
                                <p>Encapsulation: {{ $sintaks->score_encapsulation ?? '0' }}</p>
                                <p>Inheritance: {{ $sintaks->score_inheritance ?? '0' }}</p>
                                <p>Function and Logic: {{ $sintaks->score_logic_function ?? '0' }}</p>
                                <p>Project Report: {{ $sintaks->score_project_report ?? '0' }}</p>
                                <p class="font-semibold">Total: {{ 
                                    ($sintaks->score_class_object ?? 0) +
                                    ($sintaks->score_encapsulation ?? 0) +
                                    ($sintaks->score_inheritance ?? 0) +
                                    ($sintaks->score_logic_function ?? 0) +
                                    ($sintaks->score_project_report ?? 0)
                                }}</p>
                            </div>
                        </div>
                    @elseif ($sintaks && $sintaks->status_validasi == 'pending')
                        <div class="bg-yellow-100 p-4 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="text-yellow-600">Menunggu Penilaian</span>
                            <p>Silakan menunggu guru untuk memberikan penilaian.</p>
                        </div>
                    @else
                        <div class="bg-gray-100 p-4 rounded-lg mb-4">
                            <strong>Status Penilaian:</strong> <span class="text-gray-600">Belum Diminta</span>
                            <p>Silakan klik tombol di bawah untuk meminta penilaian dari guru.</p>
                        </div>
                    @endif

                    <!-- Tombol Minta Penilaian -->
                    @if (!$sintaks || $sintaks->status_validasi != 'valid')
                        <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap7') }}" method="POST">
                            @csrf
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
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