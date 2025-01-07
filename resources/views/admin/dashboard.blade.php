@extends('layouts.admin')

@section('content')
    @if (session('message'))
        <h1 class="text-gray-600 bg-blue-200 p-4">{{ session('message') }}</h1>
    @endif
@endsection
