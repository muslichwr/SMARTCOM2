@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Edit Question</h3>
                    <a href="{{ url('admin/question') }}"
                        class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Back
                    </a>
                </div>
                <div class="p-4">
                    <form action="{{ url('admin/question/' . $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block mb-1">Question Name</label>
                            <input type="text" name="name" value="{{ old('name', $question->name) }}"
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
                                    <option value="{{ $type->id }}" {{ old('question_type_id', $question->question_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('question_type_id')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media URL</label>
                            <input type="text" name="media_url" value="{{ old('media_url', $question->media_url) }}"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                            @error('media_url')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media Type</label>
                            <input type="text" name="media_type" value="{{ old('media_type', $question->media_type) }}"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                            @error('media_type')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('question.options.edit', $question->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Manage Answers</a>
                        </div>
                        <br>
                        <div class="mb-4">
                            <label class="block mb-1">Active</label>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        </div>

                        <div class="py-2 flex justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
