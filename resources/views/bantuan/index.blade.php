@extends('layouts.appF')
@section('title', 'Panduan Penggunaan - ZonaSQL')
@section('content')

<section class="bg-white dark:bg-gray-900 min-h-screen flex items-center">
    <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Panduan Penggunaan Website</h1>
                @php
                $role = Auth::user()->role_as;
                $message = '';
    
                switch ($role) {
                    case 0: // Siswa
                        $message = "Panduan penggunaan website untuk Siswa.";
                        break;
                    case 1: // Admin
                        $message = "Panduan penggunaan website untuk Admin.";
                        break;
                    case 2: // Guru
                        $message = "Panduan penggunaan website untuk Guru.";
                        break;
                    default:
                        $message = "Panduan penggunaan website untuk pengguna umum.";
                        break;
                }
            @endphp
    
            <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">
                {{ $message }}
            </p>

        <div class="relative">
            <embed src="{{ asset($pdfFilePath) }}" type="application/pdf" width="100%" height="600px" />
        </div>
    </div>
</section>

@endsection
