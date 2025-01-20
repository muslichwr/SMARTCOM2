@extends('layouts.appF')

@section('title', 'Pelajari Page')

@section('content')
    <main class="mt-3 pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white dark:bg-gray-900 antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue dark:format-invert">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                            <div>
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
                    <h1 class="mb-4 text-3xl font-extrabold leading-tight text-gray-900 lg:mb-6 lg:text-4xl dark:text-white">
                        @if ($bab)
                            Judul Bab - {{ $bab->judul }}
                        @else
                            <p class="text-red-500">Bab tidak ditemukan</p>
                        @endif
                    </h1>
                </header>

                {{-- Interactive Media Section --}}
                @if ($bab && ($bab->file_path || $bab->video_url))
                    <div class="media-container mb-4">
                        <button id="toggleMedia" class="mb-2 text-sm bg-blue-500 text-white rounded p-2 transition duration-300 ease-in-out hover:bg-blue-600 shadow-md">
                            Tampilkan Media
                        </button>
                        <div id="mediaContent" class="hidden">
                            @if ($bab->file_path)
                                <div class="pdf-viewer">
                                    <iframe src="{{ asset('storage/' . $bab->file_path) }}" width="100%" height="600px" style="border: none;"></iframe>
                                </div>
                            @endif
                            @if ($bab->video_url)
                                @php
                                    $videoId = explode('?v=', $bab->video_url)[1];
                                @endphp
                                <div class="video-responsive">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Rendered Markdown Content --}}
                {!! $renderedMarkdown !!}

                <form action="{{ url('user/materi/' . $bab->slug . '/selesaiMateri') }}" class="mb-6" method="POST">
                    @csrf
                    @method('POST')
                    <div class="flex items-center justify-between">
                        <a href="{{ url('user/materi/' . $bab->materi->slug) }}"
                            class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
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
                                <span class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-green-500 rounded-lg focus:ring-4 focus:ring-green-200 dark:focus:ring-green-900 hover:bg-green-600">
                                    Selamat anda telah menyelesaikan semua bab di materi ini.
                                </span>
                            @endif
                        @endif
                    </div>
                </form>
            </article>
        </div>
    </main>

    <style>
        .media-content {
            transition: opacity 0.5s ease-in-out;
            overflow: hidden;
        }
        .hidden {
            opacity: 0;
            height: 0;
        }
        .visible {
            opacity: 1;
            height: auto; /* Atur ini sesuai dengan konten Anda */
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleMedia');
            const mediaContent = document.getElementById('mediaContent');
        
            toggleButton.addEventListener('click', function() {
                if (mediaContent.classList.contains('visible')) {
                    mediaContent.classList.remove('visible');
                    mediaContent.classList.add('hidden');
                    toggleButton.textContent = 'Tampilkan Media';
                } else {
                    mediaContent.classList.remove('hidden');
                    mediaContent.classList.add('visible');
                    toggleButton.textContent = 'Sembunyikan Media';
                }
            });
        });
        </script>
@endsection
