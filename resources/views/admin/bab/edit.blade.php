@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            <div class="bg-white shadow-md rounded-lg">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Edit Bab</h3>
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

                    <form action="{{ url('admin/bab/' . $bab->id) }}" method="POST">
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
                                                {{ $materi->id == $bab->materi_id ? 'selected' : '' }}>
                                                {{ $materi->judul }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block mb-1">Judul</label>
                                    <input type="text" name="judul" value="{{ $bab->judul }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('judul')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="mb-4">
                                    <label class="block mb-1">Slug</label>
                                    <input type="text" name="slug" value="{{ $bab->slug }}"
                                        class="border border-gray-300 rounded-md w-full py-1 px-3 focus:outline-none focus:border-blue-500" />
                                    @error('slug')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                                <div class="mb-4">
                                    <label class="block mb-1">Isi Bab Materi</label>
                                    {{-- <textarea id="markdown-editor" name="isi"
                                        class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3">{{ $bab->isi }}</textarea> --}}
                                    <textarea id="isiBabMateri" name="isi"
                                        class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3">{{ $bab->isi }}</textarea>
                                    @error('isi')
                                        <small class="text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="content">
                                    <div class="mb-4">
                                        <label class="block mb-1">Preview Isi Bab Materi</label>
                                        {{-- <textarea id="previewIsi"
                                            class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3"
                                            readonly>{!! strip_tags($renderedMarkdown) !!}</textarea> --}}
                                        <textarea id="previewIsi"
                                            class="border border-gray-300 rounded-md w-full py-2 px-3 focus:outline-none focus:border-blue-500" rows="3"
                                            readonly>
                                            {{-- {!! markdown($bab->isi) !!} --}}
                                        </textarea>
                                        <div class="markdown-rendered">
                                            {!! $renderedMarkdown !!}
                                        </div>
                                        {{-- <div>
                                            <pre>
                                                <x-torchlight-code language='markdown | md'> --}}
                                        {{-- echo "Hello World!"; --}}
                                        {{-- {{ $bab->isi }} --}}
                                        {{-- {!! $bab->isi !!}
                                                </x-torchlight-code>
                                            </pre>
                                        </div> --}}
                                    </div>
                                </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isiBabMateri = document.getElementById('isiBabMateri');
            const previewIsi = document.getElementById('previewIsi');

            // Memasukkan nilai dari textarea isiBabMateri ke previewIsi saat halaman dimuat
            previewIsi.value = isiBabMateri.value;

            isiBabMateri.addEventListener('input', function() {
                previewIsi.value = isiBabMateri.value;
            });
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
        <script>
            // Dapatkan elemen konten
            const contentFromDatabase = document.querySelector('.content').innerHTML;

            // Konversi teks Markdown menjadi HTML menggunakan marked.js
            const renderedContent = marked(contentFromDatabase);

            // Tampilkan hasil render ke dalam elemen dengan class 'content'
            document.querySelector('.content').innerHTML = renderedContent;
        </script>
    @endpush --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('code.sql'); // Ambil semua elemen kode dengan class 'sql'
            elements.forEach((element) => {
                hljs.highlightBlock(element); // Soroti kode SQL
            });
        });
    </script>
@endsection
