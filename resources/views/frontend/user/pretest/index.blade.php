@extends('layouts.appF')

@section('title', 'Pre Test Page')

@section('content')
    <main class="pt-8 mt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article
                class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <div>
                                @if ($pretests)
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $pretests->materi->judul }}
                                    </p>
                                @else
                                    <p class="text-red-500">Pre Test tidak ditemukan</p>
                                @endif
                            </div>
                        </div>
                    </address>
                    <h1
                        class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        @if ($pretests)
                            Judul Pre Test - {{ $pretests->judulPrePost }}
                        @else
                            <p class="text-red-500">Pre Test tidak ditemukan</p>
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
                        <form class="max-w-sm mx-auto" action="{{ url('user/pretest/jawab') }}" method="POST"
                            id="pretest_form">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="pre_post_id" value="{{ $pretests->id }}">

                            @if (
                                !$preTestAttempts ||
                                    $preTestAttempts->isEmpty() ||
                                    $preTestAttempts->first()->status === null ||
                                    $preTestAttempts->first()->status == 0)
                                @foreach ($qna as $data)
                                    <h5>{{ $qcount++ }}. {{ $data['question'][0]['question'] }}</h5>
                                    <input type="hidden" name="q[]" value="{{ $data['question'][0]['id'] }}">
                                    <input type="hidden" name="ans_{{ $qcount - 1 }}" id="ans_{{ $qcount - 1 }}">


                                    <br>
                                    <textarea name="ans_{{ $qcount - 1 }}" id="ans_{{ $qcount - 1 }}"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Jawab..."></textarea>
                                    <br>
                                @endforeach
                            @else
                            @endif

                            @if (
                                !$preTestAttempts ||
                                    $preTestAttempts->isEmpty() ||
                                    $preTestAttempts->first()->status === null ||
                                    $preTestAttempts->first()->status == 0)
                                {{-- Link Github --}}
                                <h5>Menurut pemahaman anda Rest API itu apa?</h5>
                                <h7 style="color: red; font-size: smaller;">* Jawab dengan link google drive
                                </h7>
                                <textarea name="link_github"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Copy Link Google Drive Anda"></textarea>
                                <br>
                            @else
                            @endif

                            @if (
                                !$preTestAttempts ||
                                    $preTestAttempts->isEmpty() ||
                                    $preTestAttempts->first()->status === null ||
                                    $preTestAttempts->first()->status == 0)
                                <button type="submit"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                    Kirim Jawaban
                                </button>
                            @else
                                Anda telah selesai mengerjakan pre test ini
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
                                        <th class="px-4 py-2">Jawaban Pre Test</th>
                                        <th class="px-4 py-2">Nilai Jawaban</th>
                                        <th class="px-4 py-2">Start Menjawab</th>
                                        <th class="px-4 py-2">Waktu Menjawab</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $attemptCount = 0;
                                        $previous_attempt_id = null;
                                        $previous_updated_at = null;
                                    @endphp
                                    @foreach ($typedAnswers as $index => $answer)
                                        <tr class="text-center">
                                            @if ($answer['pre_test_attempt_id'] !== $previous_attempt_id)
                                                @php
                                                    $attemptCount++;
                                                    $previous_attempt_id = $answer['pre_test_attempt_id'];
                                                    $previous_updated_at = null;
                                                @endphp
                                            @endif
                                            {{-- <td class="border px-4 py-2">{{ $typedAnswers }}</td> --}}
                                            <td class="border px-4 py-2">
                                                {{ $previous_updated_at === null ? $attemptCount : '' }}</td>
                                            <td class="border px-4 py-2">{{ $answer['typed_answer'] }}</td>
                                            <td class="border px-4 py-2">
                                                {{ $answer['nilai'] }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                @php
                                                    $preTestAttempt = \App\Models\PreTestAttempt::find($answer['pre_test_attempt_id']);
                                                @endphp
                                                @if ($previous_updated_at === null)
                                                    {{ $preTestAttempt->created_at }}
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

                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Link Google Drive Pre Test</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">
                                            @php
                                                $link_github = '';
                                                if(isset($answer['pre_test_attempt_id'])) {
                                                    $preTestAttempt = \App\Models\PreTestAttempt::find($answer['pre_test_attempt_id']);
                                                    if($preTestAttempt) {
                                                        $link_github = $preTestAttempt->link_github;
                                                    }
                                                }
                                            @endphp
                                            @if($link_github)
                                                <a href="{{ $link_github }}" target="_blank">{{ $link_github }}</a>
                                            @else
                                                Anda belum mengisi link google drive untuk pre test ini
                                            @endif
                                        </td>
                                    </tr>
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
                <form action="{{ url('user/pretest/' . $pretests->id . '/selesaiPreTest') }}" class="mb-6"
                    method="POST">
                    @csrf
                    @method('POST')
                    <div class="flex items-center justify-between">
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            <x-heroicon-o-arrow-long-left class="w-5" />
                            Rehat Sejenak
                        </a>
                        {{-- @if ($pretests->status == 0 && (!$preTestAttempts || $preTestAttempts->count() == 0 || $preTestAttempts[0]->status != 2)) --}}
                    </div>
                </form>
            </article>
        </div>
    </main>
@endsection
