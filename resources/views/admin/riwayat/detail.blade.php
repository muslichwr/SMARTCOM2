@extends('layouts.admin')

@section('title', 'Progress Detail User Page')

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
                        <center>
                            <h3 class="text-lg font-semibold">Daftar Detail {{ $pp->judulPrePost }} {{ $userName->name }}
                            </h3>
                        </center>
                    </div>
                    <br>
                    <div class="p-4 mt-3">
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">No</th>
                                        <th class="px-4 py-2">Jawaban {{ $pp->judulPrePost }}</th>
                                        <th class="px-4 py-2">Nilai Jawaban {{ $pp->judulPrePost }}</th>
                                        <th class="px-4 py-2">Start Waktu Pengerjaan {{ $pp->judulPrePost }}</th>
                                        <th class="px-4 py-2">Akhir Waktu Pengerjaan {{ $pp->judulPrePost }}</th>
                                        <th class="px-4 py-2">Waktu Pengerjaan {{ $pp->judulPrePost }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        use Carbon\Carbon;
                                    @endphp

                                    @if ($pp->judulPrePost == 'Post Test' && $answerPostTest)
                                        @php
                                            $previousCreatedAt = null;
                                            $previousUpdatedAt = null;
                                            $previousTimeTaken = null;
                                        @endphp
                                        @foreach ($answerPostTest as $index => $answer)
                                            <tr class="text-center">
                                                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                                <td class="border px-4 py-2">{{ $answer->typed_answer }}</td>
                                                <td class="border px-4 py-2">{{ $answer->nilai }}</td>
                                                <td class="border px-4 py-2">
                                                    @if ($createdAt != $previousCreatedAt)
                                                        {{ $createdAt }}
                                                    @endif

                                                    @php
                                                        $previousCreatedAt = $createdAt;
                                                    @endphp
                                                </td>
                                                <td class="border px-4 py-2">

                                                    @if ($updatedAt != $previousUpdatedAt)
                                                        {{ $updatedAt }}
                                                    @endif

                                                    @php
                                                        $previousUpdatedAt = $updatedAt;
                                                    @endphp
                                                </td>
                                                <td class="border px-4 py-2">
                                                    @php
                                                        $timeTaken = \Carbon\Carbon::parse($createdAt)->diff(\Carbon\Carbon::parse($updatedAt))->format('%h hours %i minutes');
                                                    @endphp

                                                    @if ($timeTaken != $previousTimeTaken)
                                                        {{ $timeTaken }}
                                                    @endif

                                                    @php
                                                        $previousTimeTaken = $timeTaken;
                                                    @endphp
                                                </td>
                                        @endforeach
                                        </tr>
                                    @elseif ($pp->judulPrePost == 'Pre Test' && $answerPreTest)
                                    @php
                                        $previousCreatedAt = null;
                                        $previousUpdatedAt = null;
                                        $previousTimeTaken = null;
                                    @endphp
                                        @foreach ($answerPreTest as $index => $answer)
                                            <tr class="text-center">
                                                <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                                <td class="border px-4 py-2">{{ $answer->typed_answer }}</td>
                                                <td class="border px-4 py-2">{{ $answer->nilai }}</td>
                                                <td class="border px-4 py-2">
                                                    @if ($createdAt != $previousCreatedAt)
                                                        {{ $createdAt }}
                                                    @endif

                                                    @php
                                                        $previousCreatedAt = $createdAt;
                                                    @endphp
                                                </td>
                                                <td class="border px-4 py-2">

                                                    @if ($updatedAt != $previousUpdatedAt)
                                                        {{ $updatedAt }}
                                                    @endif

                                                    @php
                                                        $previousUpdatedAt = $updatedAt;
                                                    @endphp
                                                </td>
                                                <td class="border px-4 py-2">
                                                    @php
                                                        $timeTaken = \Carbon\Carbon::parse($createdAt)->diff(\Carbon\Carbon::parse($updatedAt))->format('%h hours %i minutes');
                                                    @endphp

                                                    @if ($timeTaken != $previousTimeTaken)
                                                        {{ $timeTaken }}
                                                    @endif

                                                    @php
                                                        $previousTimeTaken = $timeTaken;
                                                    @endphp
                                                </td>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <div class="my-7"></div>
                            <table class="table-auto w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-4 py-2">Link Github {{ $pp->judulPrePost }} {{ $userName->name }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($pp->judulPrePost == 'Post Test' && $answerPostTest)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">
                                            @php
                                                $link_github = '';
                                                if(isset($answer['pre_test_attempt_id'])) {
                                                    $preTestAttempt = \App\Models\PreTestAttempt::find($answer['pre_test_attempt_id']);
                                                    if($preTestAttempt) {
                                                        $link_github = $preTestAttempt->link_github;
                                                    }
                                                }
                                            @endphp
                                            @if($link_github)
                                                <a href="{{ $link_github }}" target="_blank">{{ $link_github }}</a>
                                            @else
                                                Anda belum mengisi link github untuk post test ini
                                            @endif
                                        </td>
                                    </tr>
                                    @elseif ($pp->judulPrePost == 'Pre Test' && $answerPreTest)
                                    <tr class="text-center">
                                        <td class="border px-4 py-2">
                                            @php
                                                $link_github = '';
                                                if(isset($answer['pre_test_attempt_id'])) {
                                                    $preTestAttempt = \App\Models\PreTestAttempt::find($answer['pre_test_attempt_id']);
                                                    if($preTestAttempt) {
                                                        $link_github = $preTestAttempt->link_github;
                                                    }
                                                }
                                            @endphp
                                            @if($link_github)
                                                <a href="{{ $link_github }}" target="_blank">{{ $link_github }}</a>
                                            @else
                                                Anda belum mengisi link github untuk pre test ini
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{-- {{ $materis->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection
