@extends('layouts.admin')

@section('content')
    <div class="flex justify-center mt-5">
        <div class="w-full mt-3">
            @if (session('message'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            <div class="bg-white shadow-md rounded-lg mt-3">
                <div class="bg-gray-200 px-4 py-3 rounded-t-lg">
                    <h3 class="text-lg font-semibold">Pre atau Post Test</h3>
                    {{-- <a href="{{ url('admin/latihan/create') }}"
                        class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                        <svg class="w-4 h-4 mr-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 5.757v8.486M5.757 10h8.486M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Tambah Test
                    </a> --}}
                </div>
                <br>
                <div class="p-4 mt-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Pre atau Post Test</th>
                                    <th class="px-4 py-2">Tambah Section "Soal&Jawaban Pre/Post Test"</th>
                                    <th class="px-4 py-2">Edit Section "Soal&Jawaban Pre/Post Test"</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($preposts as $index => $prepost)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">{{ $preposts->firstItem() + $index }}</td>
                                        <td class="border px-4 py-2">{{ $prepost->judulPrePost }}</td>
                                        <td class="border px-4 py-2">
                                            <button data-modal-target="authentication-modal"
                                                data-modal-toggle="authentication-modal" data-id="{{ $prepost->id }}"
                                                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 addQuestion"
                                                type="button">
                                                Tambah
                                            </button>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <button data-modal-target="authentication-modal-edit"
                                                data-modal-toggle="authentication-modal-edit" data-id="{{ $prepost->id }}"
                                                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 seeQuestions"
                                                type="button">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="border px-4 py-2">Belum Ada Pre Post yang Ditambahkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $preposts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-screen max-h-full">

            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah Section "Soal&Jawaban Pre Post" pada Latihan ini
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="authentication-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <form class="space-y-4" id="addQna">
                        @csrf
                        <div class="flex justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center">
                                    <input type="hidden" name="pre_post_id" id="addPrePostTestId">
                                    {{-- <input type="search" name="search" id="search" onkeyup="searchTable()" placeholder="Cari Soal&Jawaban Disini!"> --}}
                                    <br>
                                    <br>
                                    <table class="table-auto w-full">
                                        <thead>
                                            <th>Select</th>
                                            <th>Soal&Jawaban Pre atau Post Test</th>
                                        </thead>
                                        <tbody class="addBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah
                            Soal&Jawaban</button>
                    </form>
                </div>
            </div>
    </div>


    <!-- Main modal Edit -->
    <div id="authentication-modal-edit" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Section "Soal&Jawaban" pada Pre atau Post Test ini
                    </h3>
                    <button type="button"
                        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="authentication-modal-edit">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                        <div class="flex justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center">
                                    {{-- <input type="hidden" name="latihan_id" id="addLatihanId"> --}}
                                    {{-- <input type="search" name="search" id="search" onkeyup="searchTable()" placeholder="Cari Soal&Jawaban Disini!"> --}}
                                    <br>
                                    <br>
                                    <table class="table-auto w-full">
                                        <thead>
                                            <th>No.</th>
                                            <th>Soal&Jawaban Pre atau Post Test</th>
                                            <th>Hapus Soal&Jawaban Pre atau Post Test</th>
                                        </thead>
                                        <tbody class="seeQuestionTable">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // add question
            $('.addQuestion').click(function() {
                var id = $(this).attr('data-id');
                $('#addPrePostTestId').val(id);

                $.ajax({
                    url: "{{ route('get-soaljawabanprepost') }}",
                    type: "GET",
                    data: {
                        pre_post_id: id
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var questions = data.data;
                            var html = '';
                            if (questions.length > 0) {
                                for (let i = 0; i < questions.length; i++) {
                                    html += `
                                    <tr>
                                        <td>
                                            <input type="checkbox" value="` + questions[i]['id'] + `" name="question_ids[]">
                                        </td>
                                        <td>
                                            ` + questions[i]['questions'] + `
                                        </td>
                                    </tr>`;
                                }
                            } else {
                                html += `
                                <tr>
                                    <td>Soal&Jawaban Sudah Dipilih Semua!</td>
                                </tr>`;
                            }

                            $('.addBody').html(html);

                            //Set tinggi modal dinamis
                            // setModalHeight();

                        } else {
                            alert(data.msg);
                        }
                    }
                });

            });

            $("#addQna").submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('add-soaljawabanprepost') }}",
                    type: "POST",
                    data: formData,
                    success: function(data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            alert(data.msg);
                        }
                    }
                });
            });

            $('.seeQuestions').click(function () {
                var id = $(this).attr('data-id');

                $.ajax({

                    url: "{{ route('get-preposttest-soaljawaban') }}",
                    type: "GET",
                    data: {pre_post_id:id},
                    success: function(data) {
                        console.log(data);

                        var html = '';
                        var questions = data.data;

                        if (questions.length > 0) {

                            for(let i = 0; i < questions.length; i++) {
                                html += `
                                <tr>
                                    <td>`+(i+1)+`</td>
                                    <td>
                                        `+questions[i]['question'][0]['question']+`
                                    </td>
                                    <td>
                                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block deleteQuestion" data-id="`+questions[i]['id']+`">Hapus</button>
                                    </td>
                                </tr>
                                `;
                            }

                        } else {
                            html += `
                            <tr>
                                <td>
                                    Soal&Jawaban Pre atau Post Test belum Ada!
                                </td>
                            </tr>`;
                        }
                        $('.seeQuestionTable').html(html);
                    }

                });
            });

            $(document).on('click', '.deleteQuestion', function () {

                var id = $(this).attr('data-id');
                var obj = $(this);

                $.ajax({
                    url: "{{ route('delete-preposttest-soaljawaban') }}",
                    type: "GET",
                    data: {id:id},
                    success: function(data) {
                        if (data.success == true) {
                            obj.parent().parent().remove();
                        } else {
                            alert(data.msg);
                        }
                    }
                });

            });

            function setModalHeight() {
                var modalContentHeight = $('#authentication-modal .bg-white').height();

                $('#authentication-modal .max-h-full').css('max-height', modalContentHeight + 'px');
            }

        });
    </script>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('search');
            filter = input.value.toUpperCase();
            table = document.getElementById('questionsTable');
            tr = table.getElementByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementByTagName("td")[1];

                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

@endsection
