<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bab;
use App\Models\Materi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
    
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->storeAs('uploads/babs', Str::random(10) . '.' . $file->getClientOriginalExtension(), 'public');
        }
    
        $videoUrl = $validatedData['video_url'] ?? null;
    
        $bab = Bab::create([
            'materi_id' => $validatedData['materi_id'],
            'judul' => $validatedData['judul'],
            'slug' => Str::slug($validatedData['judul']),
            'isi' => $validatedData['isi'],
            'file_path' => $filePath,
            'video_url' => $videoUrl,
        ]);
    
        if ($bab) {
            return redirect('/admin/bab')->with('success', 'Bab Materi Berhasil Ditambahkan.');
        } else {
            return back()->withInput()->with('error', 'Gagal menyimpan Bab Materi.');
        }
    }

    public function edit(int $bab_id)
    {
        $bab = Bab::findOrFail($bab_id);
        $materis = Materi::all();
        $renderedMarkdown = Markdown::convertToHtml($bab->isi);

        return view('admin.bab.edit', compact('materis', 'bab', 'renderedMarkdown'));
    }

    public function update(BabFormRequest $request, int $bab_id)
    {
        $validatedData = $request->validated();
        $bab = Bab::findOrFail($bab_id);
    
        try {
            // Menangani Update File PDF atau Video
            $filePath = $bab->file_path;  // Default jika tidak ada file baru
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($filePath) {
                    Storage::delete($filePath);
                }
                $file = $request->file('file');
                $filePath = $file->storeAs('uploads/babs', Str::random(10) . '.' . $file->getClientOriginalExtension(), 'public');
            }
    
            $videoUrl = $validatedData['video_url'] ?? $bab->video_url;
    
            // Mengupdate Data Bab
            $bab->update([
                'materi_id' => $validatedData['materi_id'],
                'judul' => $validatedData['judul'],
                'slug' => Str::slug($validatedData['judul']),
                'isi' => $validatedData['isi'],
                'file_path' => $filePath,
                'video_url' => $videoUrl,
            ]);
    
            return redirect('/admin/bab')->with('message', 'Bab Berhasil Diupdate.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error saat update Bab: ' . $e->getMessage());
        }
    }

    public function destroy(Bab $bab)
    {
        try {
            // Hapus file dari storage jika ada
            if ($bab->file_path && Storage::disk('public')->exists($bab->file_path)) {
                Storage::disk('public')->delete($bab->file_path);
            }
            
            $bab->delete();
            alert()->success('Hore!', 'Bab Berhasil Dihapus.');
        } catch (\Exception $e) {
            alert()->error('Error!', 'Gagal menghapus bab: ' . $e->getMessage());
        }
        
        return back();
    }
}
