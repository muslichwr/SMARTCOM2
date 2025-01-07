<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bab;
use App\Models\Materi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BabFormRequest;
use App\Models\BabAttempt;
use GrahamCampbell\Markdown\Facades\Markdown;
use RealRashid\SweetAlert\Facades\Alert;

class BabController extends Controller
{
    public function index()
    {
        $babs = Bab::paginate(5);
        $title = 'Menghapus Bab!';
        $text = "Anda yakin ingin menghapus bab?";
        confirmDelete($title, $text);

        // $renderedMarkdown = Markdown::convertToHtml($babs->isi);

        return view('admin.bab.index', compact('babs'));
    }

    public function create()
    {
        $materis = Materi::all();

        return view('admin.bab.create', compact('materis'));
    }

    public function store(BabFormRequest $request)
    {
        $validatedData = $request->validated();

        $materi = Materi::findOrFail($validatedData['materi_id']);

        $bab = $materi->babs()->create([
            'materi_id' => $validatedData['materi_id'],
            'judul' => $validatedData['judul'],
            'slug' => Str::slug($validatedData['judul']),
            'isi' => $validatedData['isi'],
        ]);

        return redirect('/admin/bab')->with('message','Bab Materi Berhasil Ditambahkan.');
    }

    public function edit(int $bab_id)
    {
        $bab = Bab::findOrFail($bab_id);
        $materis = Materi::all();

        // $sqlText = $bab->isi;

        // $pattern = '/~~~SQL:(.*?)~~~/s';

        // $renderedMarkdown = preg_replace_callback(
        //     $pattern, function($matches) {
        //         return '<pre><code class="sql">' .$matches[1]. '</code></pre>';
        //     },
        //     $sqlText
        // );

        // $completeRenderedMarkdown = Markdown::convertToHtml($renderedMarkdown);

        $renderedMarkdown = Markdown::convertToHtml($bab->isi);

        return view('admin.bab.edit', compact('materis', 'bab', 'renderedMarkdown'));
    }

    public function update(BabFormRequest $request, int $bab_id)
    {
        $validatedData = $request->validated();

        // $bab = Materi::findOrFail($validatedData['materi_id'])->babs()->where('id', $bab_id)->first();
        $bab = Bab::where('id', $bab_id)->first();

        if($bab)
        {
            $bab->update([
                'materi_id' => $validatedData['materi_id'],
                'judul' => $validatedData['judul'],
                'slug' => Str::slug($validatedData['judul']),
                'isi' => $validatedData['isi'],
            ]);

            return redirect('/admin/bab')->with('message', 'Bab Berhasil Diupdate.');
        }
        else
        {
            return redirect('/admin/bab')->with('message','Tidak ada Bab Materi yang ditemukan.');
        }
    }

    public function destroy(Bab $bab)
    {
        $bab->delete();
        alert()->success('Hore!','Bab Berhasil Dihapus.');
        return back();
    }
}
