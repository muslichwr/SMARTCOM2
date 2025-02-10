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
                        <form class="max-w-sm mx-auto" action="{{ url('user/postest/jawab') }}" method="POST" id="posttest_form">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="pre_post_id" value="{{ $posttests->id }}">

                            @if(!$postTestAttempts || $postTestAttempts->isEmpty() || $postTestAttempts->first()->status == 0)
                                @foreach ($qna as $data)
                                    <div class="mb-4">
                                        <h5 class="font-semibold">{{ $loop->iteration }}. {{ $data['question'][0]['question'] }}</h5>
                                        <input type="hidden" name="q[]" value="{{ $data['question'][0]['id'] }}">
                                        <textarea name="ans_{{ $loop->iteration }}" 
                                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Tulis jawaban Anda..." 
                                            required
                                            @if($postTestAttempts->isNotEmpty() && $postTestAttempts->first()->status != 0) disabled @endif></textarea>
                                    </div>
                                @endforeach

                                @if($postTestAttempts->isEmpty() || $postTestAttempts->first()->status == 0)
                                    <div class="text-center">
                                        <button type="submit" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                            Kirim Jawaban
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6">
                                    <h3 class="text-lg font-semibold mb-2">Hasil Terakhir</h3>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ number_format($postTestAttempts->first()->total_nilai, 2) }}%
                                        <span class="text-sm ml-2">
                                            ({{ $postTestAttempts->first()->status == 2 ? 'Lulus' : 'Perlu Perbaikan' }})
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        Terakhir dikerjakan: {{ $postTestAttempts->first()->updated_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            @endif
                        </form>
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
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-sm font-semibold rounded-full {{ $attempt->status == 2 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $attempt->status == 2 ? 'Lulus' : 'Remidi' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-semibold">{{ number_format($attempt->total_nilai, 2) }}%</td>
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