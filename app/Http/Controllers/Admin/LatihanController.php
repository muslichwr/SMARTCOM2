<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\Latihan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LatihanFormRequest;

class LatihanController extends Controller
{
    public function index()
    {
        $latihans = Latihan::paginate(5);
        $title = 'Menghapus Latihan!';
        $text = "Anda yakin ingin menghapus latihan?";
        confirmDelete($title, $text);

        return view('admin.latihan.index', compact('latihans'));
    }

    public function create()
    {
        $materis = Materi::all();

        return view('admin.latihan.create', compact('materis'));
    }

    public function store(LatihanFormRequest $request)
    {
        $validatedData = $request->validated();

        $materi = Materi::findOrFail($validatedData['materi_id']);

        $latihan = $materi->latihans()->create([
            'materi_id' => $validatedData['materi_id'],
            'judulLatihan' => $validatedData['judulLatihan'],
            'slug' => Str::slug($validatedData['judulLatihan'])
        ]);

        return redirect('/admin/latihan')->with('message', 'Latihan Berhasil Ditambahkan.');
    }

    public function edit(int $latihan_id)
    {
        $latihan = Latihan::findOrFail($latihan_id);
        $materis = Materi::all();

        return view('admin.latihan.edit', compact('materis', 'latihan'));
    }

    public function update(LatihanFormRequest $request, int $latihan_id)
    {
        $validatedData = $request->validated();

        // $latihan = Materi::findOrFail($validatedData['materi_id'])->latihans()->where('id', $latihan_id)->first();

        $latihan = Latihan::findOrFail($latihan_id);

        if ($latihan) {
            $latihan->update([
                'materi_id' => $validatedData['materi_id'],
                'judulLatihan' => $validatedData['judulLatihan'],
                'slug' => Str::slug($validatedData['judulLatihan']),
            ]);

            return redirect('/admin/latihan')->with('message', 'Latihan Berhasil Diupdate.');
        } else {
            return redirect('/admin/latihan')->with('message', 'Tidak ada Latihan yang ditemukan.');
        }
    }

    public function destroy(Latihan $latihan)
    {
        $latihan->delete();
        alert()->success('Hore!', 'Latihan Berhasil Dihapus.');
        return back();
    }
}
