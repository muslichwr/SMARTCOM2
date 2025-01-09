@extends('layouts.admin')

@section('content')
<div class="flex justify-center mt-5">
    <div class="w-full mt-3">
        @if (session('message'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Questions Table -->
        <div class="bg-white shadow-md rounded-lg mt-3">
            <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                <h3 class="text-lg font-semibold">Questions</h3>
                <a href="{{ url('admin/question/create') }}"
                    class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-plus-circle class="w-5" />
                    Tambah Pertanyaan
                </a>
            </div>
            <br>
            <div class="p-4">
                <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Question Type</th>
                            <th class="px-4 py-2">Media</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $index => $question)
                            <tr class="text-center">
                                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                <td class="border px-4 py-2">{{ $question->name }}</td>
                                <td class="border px-4 py-2">
                                    {{ $question->type->display_name ?? $question->type->name ?? 'Unknown' }}
                                </td>
                                <td class="border px-4 py-2">
                                    @if($question->media_url)
                                        <a href="{{ $question->media_url }}" target="_blank">View Media</a>
                                    @else
                                        No Media
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    <a href="{{ url('admin/question/' . $question->id . '/edit') }}"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                    <form action="{{ url('admin/question/'. $question->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
