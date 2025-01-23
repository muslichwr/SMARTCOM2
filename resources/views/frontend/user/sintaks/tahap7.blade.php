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

                    <!-- Button untuk meminta penilaian -->
                    <form action="{{ url('user/materi/' . $materi->slug . '/sintaks/tahap7') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                            Minta Penilaian
                        </button>
                    </form>

                    <!-- Menampilkan Nilai dan Feedback dari Guru -->
                    @if($sintaks->score_class_object)
                        <div class="mt-4">
                            <h4 class="text-lg font-semibold">Nilai:</h4>
                            <ul>
                                <li>Class & Object: {{ $sintaks->score_class_object }}</li>
                                <li>Encapsulation: {{ $sintaks->score_encapsulation }}</li>
                                <li>Inheritance: {{ $sintaks->score_inheritance }}</li>
                                <li>Logic & Function: {{ $sintaks->score_logic_function }}</li>
                                <li>Project Report: {{ $sintaks->score_project_report }}</li>
                            </ul>

                            <h4 class="text-lg font-semibold mt-4">Feedback:</h4>
                            <p>{{ $sintaks->feedback_guru }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
