@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Add Question</h3>
                    <a href="{{ url('admin/question') }}"
                        class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Back
                    </a>
                </div>
                <div class="p-4">
                    <form action="{{ url('admin/question') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-1">Question Name</label>
                            <input type="text" name="name"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                            @error('name')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Question Type</label>
                            <select name="question_type_id" class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500">
                                <option value="">Select Question Type</option>
                                @foreach ($questionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->display_name }}</option>
                                @endforeach
                            </select>
                            @error('question_type_id')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media URL</label>
                            <input type="text" name="media_url"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                            @error('media_url')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media Type</label>
                            <input type="text" name="media_type"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                            @error('media_type')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Active</label>
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        </div>

                        <div class="py-2 flex justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
