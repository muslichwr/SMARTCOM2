@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Edit Pre / Post</h3>
                    <a href="{{ url('admin/PrePost') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <x-heroicon-o-arrow-long-left class="w-5" />
                        Kembali
                    </a>
                </div>
                <div class="p-4">
                    {{-- @if ($errors->any())
                        <div class="bg-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif --}}

                    <form action="{{ url('admin/PrePost/' . $prepost->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <br>
                        <div class="tab-content mt-2" id="myTabContent">
                            <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="mb-4">
                                    <label>Materi</label>
                                    <select name="materi_id"
                                        class="border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                        @foreach ($materis as $materi)
                                            <option value="{{ $materi->id }}"
                                                {{ $materi->id == $prepost->materi_id ? 'selected' : '' }}>
                                                {{ $materi->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">Judul Pre / Post</label>
                                    <input type="text" name="judulPrePost" value="{{ $prepost->judulPrePost }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('judul')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="mb-4">
                                    <label class="block mb-1">Slug</label>
                                    <input type="text" name="slug" value="{{ $latihan->slug }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('slug')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                            </div>

                            <div class="py-2 flex justify-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
