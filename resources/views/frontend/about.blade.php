@extends('layouts.appF')
@section('title', 'About Me')
@section('content')

<section class="bg-white mt-[100px] md:mt-10">
    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
        <div class="mr-auto place-self-center lg:col-span-7">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">Helmi Jauhari Alafadunya</h1>
            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Mahasiswa aktif S1 Prodi Pendidkan Teknologi Informasi Fakultas Teknik Universitas Negeri Surabaya Kampus Ketintang</p>
            <div class="mt-4 md:mt-8 flex gap-4">
                <a role="button" class="btn btn-circle" href="https://github.com/HelmiJauhari28"><x-bi-github class="w-10 h-10" /></a>
                <a role="button" class="btn btn-square" href="https://www.linkedin.com/in/teddyfirman/"><x-bi-linkedin class="w-10 h-10" /></a>
                <a role="button" class="btn btn-circle" href="https://web.facebook.com/helmi.jauharialafadunya.9"><x-bi-facebook class="w-10 h-10" /></a>
            </div>
        </div>
        <div class="hidden place-self-center lg:mt-0 lg:col-span-5 lg:flex">
            <img src="Helmi.jpg" alt="profil" class="rounded-full h-96">
        </div>
    </div>
</section>

@endsection
