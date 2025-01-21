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
                <h3 class="text-lg font-semibold">Daftar Kelompok PJBL</h3>
                <a href="{{ route('kelompok.create') }}" class="flex gap-1 items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded float-right mt-5">
                    <x-heroicon-o-plus-circle class="w-5" />
                    Tambah Kelompok PJBL
                </a>
            </div>
            <br>
            <div class="p-4 mt-3">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama Kelompok</th>
                                <th class="px-4 py-2">Jumlah Anggota</th>
                                <th class="px-4 py-2">Ketua</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelompok as $index => $kel)
                                <tr class="text-center">
                                    <td class="border px-4 py-2">{{ $kelompok->firstItem() + $index }}</td>
                                    <td class="border px-4 py-2">{{ $kel->kelompok }}</td>
                                    <td class="border px-4 py-2">{{ $kel->anggotas->count() }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($kel->ketua)
                                            {{ $kel->ketua->user->name }}
                                        @else
                                            Belum Ada Ketua
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('kelompok.edit', $kel->slug) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Edit</a>
                                        <a href="{{ route('kelompok.show', $kel->slug) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">Detail</a>
                                        <form action="{{ route('kelompok.destroy', $kel->slug) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelompok ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded my-1 inline-block">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border px-4 py-2" colspan="5">Belum Ada Kelompok yang Ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $kelompok->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Konfirmasi penghapusan sebelum melakukan submit
    function confirmDeletion(event) {
        if (!confirm('Apakah Anda yakin ingin menghapus kelompok ini?')) {
            event.preventDefault(); // Mencegah form untuk dikirim
        }
    }
</script>
@endsection
