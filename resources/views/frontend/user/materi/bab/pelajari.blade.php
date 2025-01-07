@extends('layouts.appF')

@section('title', 'Pelajari Page')

@section('content')
    <main class="mt-3 pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl ">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <div>
                                {{-- @foreach ($babs as $bab) --}}
                                @if ($bab)
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        Materi - {{ $bab->materi->judul }}
                                    </p>
                                @else
                                    <p class="text-red-500">Bab tidak ditemukan</p>
                                @endif
                            </div>
                        </div>
                    </address>
                    <h1
                        class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        @if ($bab)
                            Judul Bab - {{ $bab->judul }}
                        @else
                            <p class="text-red-500">Bab tidak ditemukan</p>
                        @endif

                    </h1>
                </header>
                {{-- <pre><code class="language-sql">
                        {{ $bab->isi }}
                </code></pre> --}}
                {!! $renderedMarkdown !!}
                {{-- @endforeach --}}

                <form action="{{ url('user/materi/' . $bab->slug . '/selesaiMateri') }}" class="mb-6" method="POST">
                    @csrf
                    @method('POST')
                    <div class="flex items-center justify-between">
                        <a href="{{ url('user/materi/' . $bab->materi->slug) }}"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                            <x-heroicon-o-arrow-long-left class="w-5" />
                            Rehat Sejenak
                        </a>
                        @if ($bab->status == 0 && (!$babAttempts || $babAttempts->count() == 0 || $babAttempts[0]->status != 1))
                            <button type="submit"
                                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                Selesaikan Materi
                            </button>
                        @else
                            @if ($nextBab)
                                <a href="{{ url('user/materi/' . $nextBab->slug . '/pelajari') }}"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-green-500 rounded-lg focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 hover:bg-green-600">
                                    Materi Selanjutnya
                                </a>
                            @else
                                <span"
                                    class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-green-500 rounded-lg focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 hover:bg-green-600">
                                    Selamat anda telah menyelesaikan semua bab di materi ini.
                                    </span>
                            @endif
                        @endif
                    </div>
                </form>
            </article>
        </div>
    </main>
@endsection
