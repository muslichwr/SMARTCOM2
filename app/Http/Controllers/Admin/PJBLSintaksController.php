<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\Kelompok;
use App\Models\Sintaks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PJBLSintaksController extends Controller
{
    public function index()
    {
        // Ambil semua materi beserta sintaks
        $materi = Materi::with('sintaks')->get();
    
        // Hitung jumlah kelompok untuk setiap materi
        $materi->each(function ($item) {
            $item->jumlah_kelompok = Sintaks::where('materi_id', $item->id)
                ->distinct('kelompok_id') // Hindari duplikasi kelompok
                ->count('kelompok_id');
        });
    
        return view('admin.pjbl.sintaks.index', compact('materi'));
    }

    // Daftar kelompok di materi tertentu
    public function listKelompok(Materi $materi)
    {
        // Ambil kelompok yang terdaftar di materi tertentu
        $kelompok = Kelompok::whereHas('sintaks', function($query) use ($materi) {
            $query->where('materi_id', $materi->id);
        })->get();
    
        return view('admin.pjbl.sintaks.kelompok', compact('materi', 'kelompok'));
    }

    // Detail sintaks kelompok
    public function detailSintaks(Materi $materi, Kelompok $kelompok)
    {
        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->get(); // Ambil semua sintaks kelompok di materi tertentu
        return view('admin.pjbl.sintaks.detail', compact('materi', 'kelompok', 'sintaks'));
    }

    public function updateTahap(Request $request, Materi $materi, Kelompok $kelompok)
{
    $request->validate([
        'status_tahap' => 'required|in:tahap_1,tahap_2,tahap_3,tahap_4,tahap_5,tahap_6,tahap_7',
        'orientasi_masalah' => 'nullable|string',
        'rumusan_masalah' => 'nullable|string',
        'indikator_masalah' => 'nullable|string',
        'hasil_analisis' => 'nullable|string',
        'deskripsi_proyek' => 'nullable|string',
        'tugas_anggota' => 'nullable|json',
        'file_jadwal' => 'nullable|file|mimes:pdf,doc,docx',
        'to_do_list' => 'nullable|json',
        'file_proyek' => 'nullable|file|mimes:zip,rar',
        'file_laporan' => 'nullable|file|mimes:pdf,doc,docx',
    ]);

    $sintaks = Sintaks::where('materi_id', $materi->id)
                      ->where('kelompok_id', $kelompok->id)
                      ->where('status_tahap', $request->status_tahap)
                      ->firstOrFail();

    $data = $request->only([
        'orientasi_masalah', 'rumusan_masalah', 'indikator_masalah', 
        'hasil_analisis', 'deskripsi_proyek', 'tugas_anggota', 'to_do_list'
    ]);

    // Handle file uploads
    if ($request->hasFile('file_jadwal')) {
        $data['file_jadwal'] = $request->file('file_jadwal')->store('jadwal_proyek', 'public');
    }
    if ($request->hasFile('file_proyek')) {
        $data['file_proyek'] = $request->file('file_proyek')->store('proyek', 'public');
    }
    if ($request->hasFile('file_laporan')) {
        $data['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
    }

    $sintaks->update($data);

    return redirect()->back()->with('success', 'Data tahap berhasil diperbarui.');
}

    public function validasiTahap(Request $request, Materi $materi, Kelompok $kelompok)
{
    $request->validate([
        'status_tahap' => 'required|in:tahap_1,tahap_2,tahap_3,tahap_4,tahap_5,tahap_6,tahap_7',
        'status_validasi' => 'required|in:valid,invalid,pending',
        'feedback_guru' => 'nullable|string',
    ]);

    $sintaks = Sintaks::where('materi_id', $materi->id)
                      ->where('kelompok_id', $kelompok->id)
                      ->where('status_tahap', $request->status_tahap)
                      ->firstOrFail();

    $sintaks->update([
        'status_validasi' => $request->status_validasi,
        'feedback_guru' => $request->feedback_guru,
    ]);

    return redirect()->back()->with('success', 'Status validasi dan feedback berhasil diperbarui.');
}

    // Validasi sintaks
    public function validasiSintaks(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->where('status_tahap', $request->tahap)
                          ->firstOrFail();

        $sintaks->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi berhasil diperbarui.');
    }

    // Beri nilai dan feedback
    public function beriNilai(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'score_class_object' => 'nullable|integer',
            'score_encapsulation' => 'nullable|integer',
            'score_inheritance' => 'nullable|integer',
            'score_logic_function' => 'nullable|integer',
            'score_project_report' => 'nullable|integer',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->where('status_tahap', 'tahap_7')
                          ->firstOrFail();

        $sintaks->update([
            'score_class_object' => $request->score_class_object,
            'score_encapsulation' => $request->score_encapsulation,
            'score_inheritance' => $request->score_inheritance,
            'score_logic_function' => $request->score_logic_function,
            'score_project_report' => $request->score_project_report,
            'feedback_guru' => $request->feedback_guru,
            'status_validasi' => 'valid', // Otomatis valid setelah diberi nilai
        ]);

        return redirect()->back()->with('success', 'Nilai dan feedback berhasil disimpan.');
    }
}