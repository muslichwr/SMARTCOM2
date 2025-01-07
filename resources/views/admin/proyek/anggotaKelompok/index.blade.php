@extends('layouts.admin')

@section('content')
    <section
        class="flex h-screen w-screen flex-row items-center justify-center bg-white bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:4rem_4rem]">
        <div class="mt-12 flex w-full justify-center">
            <div class="mt-12 w-full px-4 md:px-12">
                <div class="w-full rounded border border-gray-200 bg-white shadow">
                    <div class="flex flex-row items-center justify-between rounded-t bg-primary-100/25 px-4 py-2">
                        <h3 class="w-full text-center font-head font-semibold text-primary-600 md:text-lg">
                            Atur Kelompok
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <table class="mt-2.5 w-full table-auto border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-primary-100/25 font-head text-sm text-primary-600 md:text-base">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Kelompok</th>
                                        <th class="px-4 py-2">Tambah Anggota Kelompok</th>
                                        <th class="px-4 py-2">Ubah Anggota Kelompok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kelompoks as $index => $kelompok)
                                        <tr class="text-center font-body text-sm text-primary-400 md:text-base">
                                            <td class="border px-4 py-2">
                                                {{ $kelompoks->firstItem() + $index }}
                                            </td>
                                            <td class="border px-4 py-2">{{ $kelompok->kelompok }}</td>
                                            <td class="border px-4 py-2">
                                                <div class="flex flex-row justify-center space-x-4">
                                                    <span>
                                                        <button data-modal-target="modal-essai"
                                                            data-modal-toggle="modal-essai" data-id="{{ $kelompok->id }}"
                                                            class="addQuestion secondary-button" type="button">
                                                            <x-heroicon-o-plus class="mr-2 w-5" />
                                                            Tambah Anggota Kelompok
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <div class="flex flex-row justify-center space-x-4">
                                                    <span class="flex flex-row justify-center">
                                                        <button data-modal-target="modal-essai-edit"
                                                            data-modal-toggle="modal-essai-edit"
                                                            data-id="{{ $kelompok->id }}"
                                                            class="seeQuestions success-button" type="button">
                                                            <x-heroicon-o-pencil class="mr-2 w-5" />
                                                            Atur Anggota Kelompok
                                                        </button>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="border px-4 py-2 text-center font-body text-primary-200">
                                                0
                                            </td>
                                            <td class="border px-4 py-2 text-center font-body text-primary-200">
                                                Anggota Belum Tersedia.
                                            </td>
                                            <td class="border px-4 py-2 text-center font-body text-primary-200">
                                                -
                                            </td>
                                            <td class="border px-4 py-2 text-center font-body text-primary-200">
                                                -
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pt-4 font-head text-primary-400">
                            {{ $kelompoks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Essai Modal -->
    <div id="modal-essai" tabindex="-1" aria-hidden="true"
        class="fixed left-0 top-0 z-50 hidden h-[calc(100%+3rem)] w-screen items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-800/50">
        <!-- Content -->
        <div class="relative min-w-[75%] rounded bg-white shadow">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-t bg-primary-100/25 p-4">
                <h3 class="font-head text-lg font-semibold text-primary-400 md:text-xl">
                    Tambah Anggota Kelompok
                </h3>
                <button type="button"
                    class="flex w-max flex-row items-center justify-between rounded bg-red-400 p-2 font-head text-sm font-semibold text-white transition duration-200 hover:bg-red-600 md:text-base"
                    data-modal-hide="modal-essai">
                    <x-heroicon-o-x-mark class="w-5" />
                    <span class="sr-only">Close Modal</span>
                </button>
            </div>
            <!-- Body -->
            <div class="p-4 md:p-5">
                <form id="addQna">
                    @csrf
                    <div class="flex w-full justify-between">
                        <div class="flex w-full items-start">
                            <div class="flex w-full items-center">
                                <input type="hidden" name="kelompok_id" id="addLatihanId" class="invisible" />
                                <table class="w-full table-auto">
                                    <thead>
                                        <tr class="bg-primary-100/25 font-head text-sm text-primary-600 md:text-base">
                                            <th class="px-4 py-2">Pilihan</th>
                                            <th class="px-4 py-2">Siswa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="addBody font-body text-sm text-primary-400 md:text-base">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-4 w-full rounded bg-primary-400 px-4 py-2 font-head text-white">
                        Tambahkan Anggota Kelompok
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Essai -->
    <div id="modal-essai-edit" tabindex="-1" aria-hidden="true"
        class="fixed left-0 top-0 z-50 hidden h-[calc(100%+3rem)] w-screen items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-800/50">
        <!-- Content -->
        <div class="relative min-w-[75%] rounded bg-white shadow">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-t bg-primary-100/25 p-4">
                <h3 class="font-head text-lg font-semibold text-primary-400 md:text-xl">
                    Ubah Anggota Kelompok
                </h3>
                <button type="button"
                    class="flex w-max flex-row items-center justify-between rounded bg-red-400 p-2 font-head text-sm font-semibold text-white transition duration-200 hover:bg-red-600 md:text-base"
                    data-modal-hide="modal-essai-edit">
                    <x-heroicon-o-x-mark class="w-5" />
                    <span class="sr-only">Close Modal</span>
                </button>
            </div>
            <!-- Body -->
            <div class="p-4 md:p-5">
                <div class="flex w-full justify-between">
                    <div class="flex w-full items-start">
                        <div class="flex w-full items-center">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-primary-100/25 font-head text-sm text-primary-600 md:text-base">
                                        <th class="px-4 py-2">No.</th>
                                        <th class="px-4 py-2">Nama Anggota</th>
                                        <th class="px-4 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="seeQuestionTable font-body text-sm text-primary-400 md:text-base"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // add question
            $('.addQuestion').click(function() {
                var id = $(this).attr('data-id');
                $('#addLatihanId').val(id);

                $.ajax({
                    url: '{{ route('get-user') }}',
                    type: 'GET',
                    data: {
                        kelompok_id: id,
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var users = data.data;
                            console.log(users);
                            var html = '';
                            if (users.length > 0) {
                                for (let i = 0; i < users.length; i++) {
                                    html += `<tr class"text-center font-body text-sm text-primary-400 md:text-base">
                            <td class="px-4 py-2 text-center border">
                                <input type="checkbox" value="` + users[i]['id'] + `" name="user_ids[]" class="accent-primary-400">
                            </td>
                            <td class="px-4 py-2 text-center border text-body">
                                ` + users[i]['name'] + `
                            </td>
                        </tr>`;
                                }
                            } else {
                                html += `<tr class="text-sm text-center font-body text-primary-400 md:text-base">
                            <td class="px-4 py-2 border">
                                -
                            </td>
                            <td class="px-4 py-2 border">
                                Siswa di kelompok ini Telah Terpilih Semua.
                            </td>
                        </tr>`;
                            }

                            $('.addBody').html(html);
                        } else {
                            alert(data.msg);
                        }
                    },
                });
            });

            $('#addQna').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('add-user') }}',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    },
                });
            });


            $('.seeQuestions').click(function() {
                var id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('get-anggota') }}',
                    type: 'GET',
                    data: {
                        kelompok_id: id
                    },
                    success: function(data) {
                        var html = '';
                        var users = data.data;
                        console.log(users);

                        if (users.length > 0) {
                            for (let i = 0; i < users.length; i++) {
                                html += `<tr class="text-sm text-center font-body text-primary-400 md:text-base">
                            <td class="px-4 py-2 border">` + (i + 1) + `</td>
                            <td class="px-4 py-2 border">
                                ` + users[i]['users']['name'] + `
                            </td>
                            <td class="px-4 py-2 text-center border">
                                <span class="flex justify-center items-center">
                                    <button class="dangers-button deleteQuestion" data-id="` + users[i]['id'] + `">Hapus</button>
                                </span>
                            </td>
                        </tr>`;
                            }
                        } else {
                            html += `<tr class="text-sm text-center font-body text-primary-400 md:text-base">
                            <td class="px-2.5 py-2 font-body border text-center text-primary-400">
                                0
                            </td>
                            <td class="px-2.5 py-2 font-body border text-center text-primary-400">
                                Anggota kelompok Belum Ditambahkan.
                            </td>
                            <td class="px-2.5 py-2 font-body border text-center text-primary-400">
                                -
                            </td>
                        </tr>`;
                        }
                        $('.seeQuestionTable').html(html);
                    },
                });
            });

            $(document).on('click', '.deleteQuestion', function() {
                var id = $(this).attr('data-id');
                var obj = $(this);

                $.ajax({
                    url: '{{ route('delete-anggota') }}',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            obj.parent().parent().remove();
                            location.reload()
                        } else {
                            alert(data.msg);
                        }
                    },
                });
            });

            function setModalHeight() {
                var modalContentHeight = $('#modal-essai .bg-white').height();

                $('#modal-essai .max-h-full').css(
                    'max-height',
                    modalContentHeight + 'px',
                );
            }
        });
    </script>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            table = document.getElementById('questionsTable');
            tr = table.getElementByTagName('tr');
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementByTagName('td')[1];

                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
@endsection
