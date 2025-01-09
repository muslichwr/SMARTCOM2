@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Manage Options for: {{ $question->name }}</h3>
                </div>
                <div class="p-4">
                    <form action="{{ route('question.options.store', $question->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-1">Option Name</label>
                            <input type="text" name="name"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Correct Option</label>
                            <input type="checkbox" name="is_correct" value="1" />
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media URL</label>
                            <input type="text" name="media_url"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1">Media Type</label>
                            <input type="text" name="media_type"
                                class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                        </div>

                        <div class="py-2 flex justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Add Option</button>
                        </div>
                    </form>

                    <!-- Existing Options -->
                    <div class="mt-5">
                        <h4 class="text-md font-semibold">Existing Options</h4>
                        <table class="table-auto w-full border-collapse border border-gray-300 mt-3">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Correct</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($question->options as $option)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $option->name }}</td>
                                        <td class="border px-4 py-2">{{ $option->is_correct ? 'Yes' : 'No' }}</td>
                                        <td class="border px-4 py-2">
                                            <form action="{{ route('question.options.destroy', $option->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
