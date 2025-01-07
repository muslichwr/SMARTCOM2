@extends('layouts.appF')

@section('title', 'Kerjakan Latihan Page')

@section('content')
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article
                class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <div>
                                {{-- @foreach ($babs as $bab) --}}
                                @if ($latihans)
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        Materi - {{ $latihans->materi->judul }}
                                    </p>
                                @else
                                    <p class="text-red-500">Latihan tidak ditemukan</p>
                                @endif
                            </div>
                        </div>
                    </address>
                    <h1
                        class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        @if ($latihans)
                            Judul Latihan - {{ $latihans->judulLatihan }}
                        @else
                            <p class="text-red-500">Latihan tidak ditemukan</p>
                        @endif

                    </h1>
                </header>
                {{-- <pre><code class="language-sql">
                        {{ $bab->isi }}
                </code></pre> --}}
                {{-- {!! $renderedMarkdown !!} --}}

                {{-- PESAN --}}
                @if (session('message'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                {{-- FORM JAWAB --}}

                @php
                    $qcount = 1;
                @endphp

                @if ($success == true)
                    @if (count($qna) > 0)
                        <form class="max-w-sm mx-auto" action="{{ url('user/latihan/jawab') }}" method="POST"
                            id="latihan_form">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="latihan_id" value="{{ $latihans->id }}">

                            @foreach ($qna as $data)
                                <h5>{{ $qcount++ }}. {{ $data['question'][0]['question'] }}</h5>
                                <input type="hidden" name="q[]" value="{{ $data['question'][0]['id'] }}">
                                <input type="hidden" name="ans_{{ $qcount - 1 }}" id="ans_{{ $qcount - 1 }}">

                                @if (
                                    $latihans->status == 0 &&
                                        (!$latihanAttempts ||
                                            $latihanAttempts->isEmpty() ||
                                            $latihanAttempts->sortByDesc('updated_at')->first()->status != 2))
                                    <br>
                                    <textarea name="ans_{{ $qcount - 1 }}" id="ans_{{ $qcount - 1 }}"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Jawab..."></textarea>
                                    <br>
                                @else
                                    Jawaban Anda Benar: {{ $data['question'][0]['jawaban']['answer'] }}
                                @endif
                            @endforeach

                            <!-- Tombol di luar foreach -->
                            @if (
                                $latihans->status == 0 &&
                                    (!$latihanAttempts ||
                                        $latihanAttempts->isEmpty() ||
                                        $latihanAttempts->sortByDesc('updated_at')->first()->status != 2))
                                <button type="submit"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                    Cek Jawaban
                                </button>
                            @endif
                        </form>

                        @php
                            $attemptCount = 1;
                        @endphp

                        <br>
                        Riwayat Pengerjaan:
                        <h5>
                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Percobaan Menjawab</th>
                                        <th class="px-4 py-2">Percobaan Jawaban</th>
                                        <th class="px-4 py-2">Status Jawaban</th>
                                        <th class="px-4 py-2">Waktu Menjawab</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $attemptCount = 0;
                                        $previous_attempt_id = null;
                                        $previous_updated_at = null;
                                    @endphp
                                    @foreach ($typedAnswers as $answer)
                                        <tr class="text-center">
                                            @if ($answer['latihan_attempt_id'] !== $previous_attempt_id)
                                                @php
                                                    $attemptCount++;
                                                    $previous_attempt_id = $answer['latihan_attempt_id'];
                                                    $previous_updated_at = null;
                                                @endphp
                                            @endif
                                            <td class="border px-4 py-2">
                                                {{ $previous_updated_at === null ? $attemptCount : '' }}</td>
                                            <td class="border px-4 py-2">
                                                {{ $previous_updated_at === null ? "Percobaan ke - $attemptCount:" : '' }}
                                            </td>
                                            <td class="border px-4 py-2">{{ $answer['typed_answer'] }}</td>
                                            <td class="border px-4 py-2">
                                                @if ($previous_updated_at === null)
                                                    @if ($answer['status'] == 1)
                                                        <svg class="w-6 h-6 text-red-500" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    @elseif ($answer['status'] == 2)
                                                        <svg class="w-6 h-6 text-green-500" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">
                                                @if ($previous_updated_at === null)
                                                    {{ $answer['updated_at'] }}
                                                @endif
                                            </td>
                                            @php
                                                $previous_updated_at = $answer['updated_at'];
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </h5>
                    @else
                        <h3 style="color:red;" class="text-center">Latihan belum tersedia!</h3>
                    @endif
                @else
                    <h3 style="color:red;" class="text-center">{{ $msg }}</h3>
                @endif

                {{-- @endforeach --}}
                <br>
                <form action="{{ url('user/materi/' . $latihans->slug . '/selesaiLatihan') }}" class="mb-6" method="POST">
                    @csrf
                    @method('POST')
                    <div class="flex items-center justify-between">
                        <a href="{{ url('user/latihan/' . $latihans->materi->slug) }}"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            <x-heroicon-o-arrow-long-left class="w-5" />
                            Rehat Sejenak
                        </a>
                        {{-- @if ($latihans->status == 0 && (!$latihanAttempts || $latihanAttempts->count() == 0 || $latihanAttempts[0]->status != 2)) --}}
                        @if (
                            $latihans->status == 0 &&
                                (!$latihanAttempts ||
                                    $latihanAttempts->isEmpty() ||
                                    $latihanAttempts->sortByDesc('updated_at')->first()->status != 2))
                            {{-- <button type="submit"
                                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                Selesaikan Latihan
                            </button> --}}
                            <span
                                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                                Selesaikan Latihan !
                            </span>
                        @else
                            @if ($nextLatihan)
                                <a href="{{ url('user/latihan/' . $nextLatihan->slug . '/kerjakan') }}"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-green-500 rounded-lg focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 hover:bg-green-600">
                                    Latihan Selanjutnya
                                </a>
                            @else
                                <span"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-green-500 rounded-lg focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 hover:bg-green-600">
                                    Selamat anda telah menyelesaikan semua latihan di materi ini.
                                    </span>
                            @endif
                        @endif
                    </div>
                </form>
            </article>
        </div>
    </main>
@endsection
