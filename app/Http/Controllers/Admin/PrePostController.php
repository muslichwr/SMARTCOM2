<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\PrePost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrePostFormRequest;

class PrePostController extends Controller
{
    public function index()
    {
        $preposts = PrePost::paginate(5);
        $title = 'Menghapus Pre / Post Test!';
        $text = "Anda yakin ingin menghapus pre / post test?";
        confirmDelete($title, $text);

        return view('admin.prepost.index', compact('preposts'));
    }

    public function create()
    {
        $materis = Materi::all();

        return view('admin.prepost.create', compact('materis'));
    }

    public function store(PrePostFormRequest $request)
    {
        $validatedData = $request->validated();

        $materi = Materi::findOrFail($validatedData['materi_id']);

        $prepost = $materi->preposts()->create([
            'materi_id' => $validatedData['materi_id'],
            'judulPrePost' => $validatedData['judulPrePost'],
            'slug' => Str::slug($validatedData['judulPrePost']),
        ]);

        return redirect('/admin/PrePost')->with('message', 'Pre atau Post Berhasil Ditambahkan');
    }

    public function edit(int $prepost_id)
    {
        $prepost = PrePost::findOrFail($prepost_id);
        $materis = Materi::all();

        return view('admin.prepost.edit', compact('materis', 'prepost'));
    }

    public function update(PrePostFormRequest $request, int $prepost_id)
    {
        $validatedData = $request->validated();

        $prepost = PrePost::findOrFail($prepost_id);

        if ($prepost) {
            $prepost->update([
                'materi_id' => $validatedData['materi_id'],
                'judulPrePost' => $validatedData['judulPrePost'],
                'slug' => Str::slug($validatedData['judulPrePost']),
            ]);

            return redirect('admin/PrePost')->with('message', 'Pre atau Post Berhasil Diupdate');
        } else {
            return redirect('/admin/PrePost')->with('message', 'Tidak ada Pre atau Post yang ditemukan');
        }
    }

    public function destroy(PrePost $PrePost)
    {
        $PrePost->delete();
        alert()->success('Hore!', 'Pre atau Post Berhasil Dihapus');

        return back();
    }
}
