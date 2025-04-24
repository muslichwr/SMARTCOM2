<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MateriFormRequest;
use RealRashid\SweetAlert\Facades\Alert;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::withCount('babs')->orderBy('id', 'ASC')->paginate(5);

        $title = 'Menghapus Materi!';
        $text = "Anda yakin ingin menghapus materi?";
        confirmDelete($title, $text);

        return view('admin.materi.index', ['materis' => $materis]);
    }

    public function create()
    {
        return view('admin.materi.create');
    }

    public function store(MateriFormRequest $request)
    {
        $validatedData = $request->validated();

        $materi = new Materi;
        $materi->judul = $validatedData['judul'];
        $materi->slug = Str::slug($validatedData['judul']);
        $materi->deskripsi = $validatedData['deskripsi'];
        $materi->pjbl_sintaks_active = $request->has('pjbl_sintaks_active') ? true : false;
        $materi->save();

        return redirect('admin/materi')->with('message', 'Materi berhasil ditambahkan.');
    }

    public function edit(Materi $materi)
    {
        return view('admin.materi.edit', compact('materi'));
    }

    public function update(MateriFormRequest $request, $materi)
    {
        $validatedData = $request->validated();

        $materi = Materi::findOrFail($materi);

        $materi->judul = $validatedData['judul'];
        $materi->slug = Str::slug($validatedData['judul']);
        $materi->deskripsi = $validatedData['deskripsi'];
        $materi->pjbl_sintaks_active = $request->has('pjbl_sintaks_active') ? true : false;
        $materi->update();

        return redirect('admin/materi')->with('message', 'Materi berhasil diupdate.');
    }

    public function destroy(Materi $materi) {
        $materi->delete();
        alert()->success('Hore!','Materi Berhasil Dihapus.');
        return back();
    }
}
