@extends('layouts.appF')
@section('title', 'Homepage - ZonaSQL')
@section('content')

    <section class="bg-white dark:bg-gray-900 min-h-screen flex items-center">
        <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Belajar Lebih Cepat, Berprestasi Lebih Tinggi</h1>
            <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Kami menawarkan metode pembelajaran terkini yang dirancang untuk mempercepat proses belajar Anda. Raih prestasi lebih tinggi dengan cara yang lebih cerdas.</p>
            @guest
            <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                <a href="/login" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">Get started</a>
            </div>
            @endguest
        </div>
    </section>

@endsection
