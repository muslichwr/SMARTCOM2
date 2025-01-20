@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Tambah Bab</h3>
                    <a href="{{ url('admin/bab') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
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

                    <form action="{{ url('admin/bab') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <br>
                        <div class="tab-content mt-2" id="myTabContent">
                            <div class="tab-pane fade border p-3 show active" id="home-tab-pane" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="mb-4">
                                    <label>Materi</label>
                                    <select name="materi_id"
                                        class="border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                                        @foreach ($materis as $materi)
                                            <option value="{{ $materi->id }}">{{ $materi->judul }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">Judul</label>
                                    <input type="text" name="judul"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('judul')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="mb-4">
                                    <label class="block mb-1">Slug</label>
                                    <input type="text" name="slug"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('slug')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                                <div class="mb-4">
                                    <label class="block mb-1">Isi Bab Materi</label>
                                    <textarea id="isiBabMateri" name="isi"
                                        class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3"></textarea>
                                    @error('isi')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">Preview Isi Bab Materi</label>
                                    <textarea id="previewIsi"
                                        class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3"
                                        readonly></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">File PDF/Video</label>
                                    <input type="file" name="file" class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" accept=".pdf,.mp4">
                                    @error('file')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">URL Video YouTube</label>
                                    <input type="url" name="video_url" class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" placeholder="Masukkan URL video YouTube">
                                    @error('video_url')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="flex flex-col space-y-2">
                                    <label for="editor" class="text-gray-600 font-semibold">Content</label>
                                    <div id="editor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
                                </div> --}}
                            </div>

                            <div class="py-2 flex justify-end">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const isiBabMateri = document.getElementById('isiBabMateri');
        const previewIsi = document.getElementById('previewIsi');

        isiBabMateri.addEventListener('input', function() {
            previewIsi.value = isiBabMateri.value;
        });
    </script>
    {{-- @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script src="{{ asset('js/marked.js') }}"></script>

        <script>
            const markdownEditor = new EasyMDE({
                showIcons: ['strikethrough', 'code', 'table', 'redo', 'heading', 'undo', 'heading-bigger',
                    'heading-smaller', 'heading-1', 'heading-2', 'heading-3', 'clean-block', 'horizontal-rule'
                ],
                element: document.getElementById('markdown-editor')
            });

            const previewIsi = document.getElementById('previewIsi');

            markdownEditor.codemirror.on('change', function() {
                const markdownContent = markdownEditor.value();
                const renderedHTML = marked(markdownContent);
                previewIsi.textContent = renderedHTML;
            });
        </script>
    @endpush --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> --}}

    {{-- <script src="{{ asset('js/marked.js') }}"></script> --}}
    {{-- <script>
        const isiBabMateri = document.getElementById('isiBabMateri');
        const previewIsi = document.getElementById('previewIsi');

        isiBabMateri.addEventListener('input', function() {
            const markdownContent = isiBabMateri.value;
            const renderedHTML = marked(markdownContent);
            previewIsi.innerHTML = renderedHTML;
        });
    </script> --}}
@endsection
