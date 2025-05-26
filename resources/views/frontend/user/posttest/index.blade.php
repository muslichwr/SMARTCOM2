@extends('layouts.appF')

@section('title', 'Post Test Page')

@section('content')
    <main class="pt-8 mt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <!-- Header Section -->
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <div>
                                @if ($posttests)
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $posttests->materi->judul }}
                                    </p>
                                @else
                                    <p class="text-red-500">Post Test tidak ditemukan</p>
                                @endif
                            </div>
                        </div>
                    </address>
                    <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        @if ($posttests)
                            {{ $posttests->judulPrePost }}
                        @else
                            <p class="text-red-500">Post Test tidak ditemukan</p>
                        @endif
                    </h1>
                </header>

                <!-- Pesan Feedback -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Form Jawaban -->
                @if ($success)
                    @if(count($qna) > 0)
                        @php
                            $needsRevision = false;
                            $lastAttempt = null;
                            
                            if ($postTestAttempts && $postTestAttempts->isNotEmpty()) {
                                $lastAttempt = $postTestAttempts->first();
                                $needsRevision = $lastAttempt->total_nilai < 80 && $lastAttempt->status != 2;
                            }
                        @endphp

                        @if(!$postTestAttempts || $postTestAttempts->isEmpty() || $needsRevision)
                            <!-- Pesan revisi jika nilai di bawah 80 -->
                            @if ($needsRevision)
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Nilai Anda <span class="font-bold">{{ number_format($lastAttempt->total_nilai, 2) }}%</span> (di bawah 80%). 
                                                Silakan kerjakan kembali Post Test untuk meningkatkan nilai Anda.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <form class="max-w-sm mx-auto" action="{{ url('user/postest/jawab') }}" method="POST" id="posttest_form">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="pre_post_id" value="{{ $posttests->id }}">

                                @foreach ($qna as $data)
                                    <div class="mb-4">
                                        <h5 class="font-semibold">{{ $loop->iteration }}. {{ $data['question'][0]['question'] }}</h5>
                                        <input type="hidden" name="q[]" value="{{ $data['question'][0]['id'] }}">
                                        <textarea name="ans_{{ $loop->iteration }}" 
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Tulis jawaban Anda..." 
                                            required></textarea>
                                    </div>
                                @endforeach

                                <div class="text-center">
                                    <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                        {{ $needsRevision ? 'Kirim Revisi' : 'Kirim Jawaban' }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                                <h3 class="text-lg font-semibold mb-2">Hasil Terakhir</h3>
                                <p class="text-2xl font-bold {{ $postTestAttempts->first()->total_nilai >= 80 ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ number_format($postTestAttempts->first()->total_nilai, 2) }}%
                                    <span class="text-sm ml-2">
                                        ({{ $postTestAttempts->first()->status == 2 ? 'Lulus' : 'Perlu Perbaikan' }})
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    Terakhir dikerjakan: {{ $postTestAttempts->first()->updated_at->format('d M Y H:i') }}
                                </p>
                                
                                @if ($postTestAttempts->first()->total_nilai < 80)
                                    <div class="mt-4">
                                        <p class="text-sm text-yellow-700 mb-2">
                                            Nilai Anda di bawah standar kelulusan (80%). Apakah Anda ingin mencoba lagi?
                                        </p>
                                        <a href="{{ url('user/postest') }}" class="inline-flex items-center py-2 px-3 text-xs font-medium text-center text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300">
                                            Coba Lagi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <h3 class="text-center text-red-500">Latihan belum tersedia!</h3>
                    @endif
                @else
                    <h3 class="text-center text-red-500">{{ $msg }}</h3>
                @endif

                <!-- Riwayat Attempt -->
                @if($success && $postTestAttempts->isNotEmpty())
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-4">Riwayat Percobaan</h3>
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">#</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Nilai</th>
                                        <th scope="col" class="px-6 py-3">Mulai</th>
                                        <th scope="col" class="px-6 py-3">Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($postTestAttempts as $attempt)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $attempt->total_nilai < 80 ? 'bg-yellow-50' : '' }}">
                                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attempt->status == 2 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $attempt->status == 2 ? 'Lulus' : 'Perlu Revisi' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-semibold {{ $attempt->total_nilai < 80 ? 'text-yellow-600' : 'text-green-600' }}">
                                                {{ number_format($attempt->total_nilai, 2) }}%
                                            </td>
                                            <td class="px-6 py-4">{{ $attempt->created_at->format('d M Y H:i') }}</td>
                                            <td class="px-6 py-4">{{ $attempt->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Navigasi -->
                <div class="mt-8 text-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                        <x-heroicon-o-arrow-long-left class="w-5 mr-2" />
                        Kembali ke Beranda
                    </a>
                </div>
            </article>
        </div>
    </main>
@endsection