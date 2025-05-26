<?php

namespace App\Http\Controllers\Admin;

use App\Models\Materi;
use App\Models\Kelompok;
use App\Models\SintaksBaru;
use App\Models\SintaksTahapSatu;
use App\Models\SintaksTahapDua;
use App\Models\SintaksTahapTiga;
use App\Models\SintaksTahapEmpat;
use App\Models\SintaksTahapEmpatTugas;
use App\Models\SintaksTahapLima;
use App\Models\SintaksTahapEnam;
use App\Models\SintaksTahapTuju;
use App\Models\SintaksTahapTujuNilai;
use App\Models\SintaksTahapDelapan;
use App\Models\SintaksTahapDelapanRefleksi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PJBLSintaksController extends Controller
{
    public function index()
    {
        // Ambil semua materi
        $materi = Materi::all();
    
        // Hitung jumlah kelompok untuk setiap materi
        $materi->each(function ($item) {
            $item->kelompok_count = SintaksBaru::where('materi_id', $item->id)
                ->distinct('kelompok_id')
                ->count('kelompok_id');
        });
    
        return view('admin.pjbl.sintaks.index', compact('materi'));
    }

    // Aktifkan/Nonaktifkan PJBL Sintaks pada materi
    public function togglePJBLSintaks(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        $materi->pjbl_sintaks_active = !$materi->pjbl_sintaks_active;
        $materi->save();

        $status = $materi->pjbl_sintaks_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "PJBL Sintaks berhasil $status untuk materi {$materi->judul}");
    }

    // Daftar kelompok di materi tertentu
    public function listKelompok(Materi $materi)
    {
        // Cek apakah PJBL Sintaks aktif untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('admin.pjbl.sintaks.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini.');
        }

        // Ambil kelompok yang terdaftar di materi tertentu
        $kelompok = Kelompok::whereHas('sintaks', function($query) use ($materi) {
            $query->where('materi_id', $materi->id);
        })->get();
    
        return view('admin.pjbl.sintaks.kelompok', compact('materi', 'kelompok'));
    }

    // Detail sintaks kelompok
    public function detailSintaks(Materi $materi, Kelompok $kelompok)
    {
        // Cek apakah PJBL Sintaks aktif untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('admin.pjbl.sintaks.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini.');
        }

        $anggotaKelompok = $kelompok->anggotas;
        
        // Ambil sintaks baru untuk kelompok ini
        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->first();
        
        if (!$sintaks) {
            // Jika tidak ada, buat sintaks baru
            $sintaks = SintaksBaru::create([
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
                'status_validasi' => 'pending'
            ]);
        }
        
        // Load semua tahapan sintaks
        $sintaks->load([
            'sintaksTahapSatu', 
            'sintaksTahapDua', 
            'sintaksTahapTiga', 
            'sintaksTahapEmpat.tasks', 
            'sintaksTahapLima', 
            'sintaksTahapEnam', 
            'sintaksTahapTuju.nilaiIndividu', 
            'sintaksTahapDelapan.refleksiIndividu'
        ]);

        return view('admin.pjbl.sintaks.detail', compact('materi', 'anggotaKelompok', 'kelompok', 'sintaks'));
    }

    // Validasi untuk tahap 1 (Orientasi Masalah)
    public function validasiTahapSatu(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapSatu = $sintaks->sintaksTahapSatu;
        
        if (!$tahapSatu) {
            return redirect()->back()->with('error', 'Tahap 1 belum diisi oleh kelompok.');
        }
        
        $tahapSatu->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 1 berhasil diperbarui.');
    }

    // Validasi untuk tahap 2 (Rancangan Proyek)
    public function validasiTahapDua(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapDua = $sintaks->sintaksTahapDua;
        
        if (!$tahapDua) {
            return redirect()->back()->with('error', 'Tahap 2 belum diisi oleh kelompok.');
        }
        
        $tahapDua->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 2 berhasil diperbarui.');
    }

    // Validasi untuk tahap 3 (Jadwal Proyek)
    public function validasiTahapTiga(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapTiga = $sintaks->sintaksTahapTiga;
        
        if (!$tahapTiga) {
            return redirect()->back()->with('error', 'Tahap 3 belum diisi oleh kelompok.');
        }
        
        $tahapTiga->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 3 berhasil diperbarui.');
    }

    // Validasi untuk tahap 4 (Pelaksanaan Proyek)
    public function validasiTahapEmpat(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapEmpat = $sintaks->sintaksTahapEmpat;
        
        if (!$tahapEmpat) {
            return redirect()->back()->with('error', 'Tahap 4 belum diisi oleh kelompok.');
        }
        
        $tahapEmpat->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 4 berhasil diperbarui.');
    }

    // Validasi untuk tahap 5 (Hasil Karya Proyek)
    public function validasiTahapLima(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
            'deskripsi_hasil' => 'nullable|string',
            'file_hasil_karya' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip|max:20480',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapLima = $sintaks->sintaksTahapLima;
        
        if (!$tahapLima) {
            if ($request->has('deskripsi_hasil') || $request->hasFile('file_hasil_karya')) {
                $tahapLima = new \App\Models\SintaksTahapLima();
                $tahapLima->sintaks_id = $sintaks->id;
                $tahapLima->status_validasi = 'pending';
                $tahapLima->status = 'proses';
                
                if ($request->has('deskripsi_hasil')) {
                    $tahapLima->deskripsi_hasil = $request->deskripsi_hasil;
                }
                
                $tahapLima->save();
                
                $this->processFileUploads($request, $tahapLima);
                
                return redirect()->back()->with('success', 'Data tahap 5 berhasil disimpan.');
            }
            return redirect()->back()->with('error', 'Tahap 5 belum diisi oleh kelompok.');
        }
        
        $dataUpdated = false;
        
        if ($request->has('deskripsi_hasil')) {
            $tahapLima->deskripsi_hasil = $request->deskripsi_hasil;
            $dataUpdated = true;
        }
        
        $filesUpdated = $this->processFileUploads($request, $tahapLima);
        
        // Update status validasi dan feedback
        $tahapLima->status_validasi = $request->status_validasi;
        $tahapLima->feedback_guru = $request->feedback_guru;
        $tahapLima->save();

        return redirect()->back()->with('success', 'Status validasi Tahap 5 berhasil diperbarui.');
    }

    // Validasi untuk tahap 6 (Presentasi Proyek)
    public function validasiTahapEnam(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
            'link_presentasi' => 'nullable|url',
            'jadwal_presentasi' => 'nullable|date_format:Y-m-d\TH:i',
            'catatan_presentasi' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapEnam = $sintaks->sintaksTahapEnam;
        
        if (!$tahapEnam) {
            if ($request->has('link_presentasi') || $request->has('jadwal_presentasi') || 
                $request->has('catatan_presentasi')) {
                $tahapEnam = new \App\Models\SintaksTahapEnam();
                $tahapEnam->sintaks_id = $sintaks->id;
                $tahapEnam->status_validasi = 'pending';
                $tahapEnam->status = 'proses';
                
                if ($request->has('link_presentasi')) {
                    $tahapEnam->link_presentasi = $request->link_presentasi;
                }
                
                if ($request->has('jadwal_presentasi')) {
                    $tahapEnam->jadwal_presentasi = $request->jadwal_presentasi;
                }
                
                if ($request->has('catatan_presentasi')) {
                    $tahapEnam->catatan_presentasi = $request->catatan_presentasi;
                }
                
                $tahapEnam->save();
                
                
                return redirect()->back()->with('success', 'Data tahap 6 berhasil disimpan.');
            }
            
            return redirect()->back()->with('error', 'Tahap 6 belum diisi oleh kelompok.');
        }
        
        $dataUpdated = false;
        
        if ($request->has('link_presentasi')) {
            $tahapEnam->link_presentasi = $request->link_presentasi;
            $dataUpdated = true;
        }
        
        if ($request->has('jadwal_presentasi')) {
            $tahapEnam->jadwal_presentasi = $request->jadwal_presentasi;
            $dataUpdated = true;
        }
        
        if ($request->has('catatan_presentasi')) {
            $tahapEnam->catatan_presentasi = $request->catatan_presentasi;
            $dataUpdated = true;
        }
        
        
        if ($dataUpdated) {
            $tahapEnam->save();
            return redirect()->back()->with('success', 'Data tahap 6 berhasil diperbarui.');
        }

        return redirect()->back()->with('success', 'Status validasi Tahap 6 berhasil diperbarui.');
    }

    // Beri nilai dan feedback untuk tahap 7 (Penilaian)
    public function beriNilai(Request $request, Materi $materi, Kelompok $kelompok)
    {
        // Cek apakah PJBL Sintaks aktif untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('admin.pjbl.sintaks.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini.');
        }

        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nilai_kriteria' => 'required|array',
            'nilai_kriteria.kriteria.*.nama' => 'required|string',
            'nilai_kriteria.kriteria.*.nilai' => 'required|integer|min:0|max:100',
            'nilai_kriteria.kriteria.*.bobot' => 'required|integer|min:1|max:5',
            'total_nilai_individu' => 'required|numeric|min:0|max:100',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                           ->where('kelompok_id', $kelompok->id)
                           ->firstOrFail();
        
        // Ambil atau buat SintaksTahapTuju
        $tahapTuju = $sintaks->sintaksTahapTuju;
        if (!$tahapTuju) {
            $tahapTuju = SintaksTahapTuju::create([
                'sintaks_id' => $sintaks->id,
                'status_validasi' => 'valid',
                'feedback_guru' => $request->feedback_guru,
                'status' => 'selesai'
            ]);
        } else {
            $tahapTuju->update([
                'status_validasi' => 'valid',
                'feedback_guru' => $request->feedback_guru,
                'status' => 'selesai'
            ]);
        }

        // Simpan nilai individu
        SintaksTahapTujuNilai::updateOrCreate(
            [
                'sintaks_penilaian_id' => $tahapTuju->id,
                'user_id' => $request->user_id
            ],
            [
                'nilai_kriteria' => $request->nilai_kriteria,
                'total_nilai_individu' => $request->total_nilai_individu
            ]
        );

        // Update total nilai di sintaks utama (rata-rata dari semua nilai individu)
        $nilaiIndividu = $tahapTuju->nilaiIndividu;
        if ($nilaiIndividu->count() > 0) {
            $totalNilai = $nilaiIndividu->avg('total_nilai_individu');
            $sintaks->update(['total_nilai' => round($totalNilai)]);
        }

        return redirect()->back()->with('success', 'Nilai dan feedback berhasil disimpan.');
    }

    // Validasi untuk tahap 8 (Evaluasi dan Refleksi)
    public function validasiTahapDelapan(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapDelapan = $sintaks->sintaksTahapDelapan;
        
        if (!$tahapDelapan) {
            return redirect()->back()->with('error', 'Tahap 8 belum diisi oleh kelompok.');
        }
        
        $tahapDelapan->status_validasi = $request->status_validasi;
        $tahapDelapan->feedback_guru = $request->feedback_guru;
        $tahapDelapan->save();
        
        // Jika validasi menjadi valid, update status tahap menjadi selesai
        if ($request->status_validasi === 'valid') {
            $tahapDelapan->status = 'selesai';
            $tahapDelapan->save();
        }
        
        return redirect()->back()->with('success', 'Status validasi Tahap 8 berhasil diperbarui.');
    }

    // Metode wrapper untuk validasi semua tahap
    public function validasiTahap(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'tahap' => 'required|in:tahap_satu,tahap_dua,tahap_tiga,tahap_empat,tahap_lima,tahap_enam,tahap_tuju,tahap_delapan',
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
            'orientasi_masalah' => 'nullable|string',
            'rumusan_masalah' => 'nullable|string',
            'file_indikator_masalah' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_hasil_analisis' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'deskripsi_rancangan' => 'nullable|string',
            'file_rancangan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'deskripsi_hasil' => 'nullable|string',
            'file_hasil_karya' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip|max:20480',
            'link_presentasi' => 'nullable|url',
            'jadwal_presentasi' => 'nullable|date_format:Y-m-d\TH:i',
            'catatan_presentasi' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        switch ($request->tahap) {
            case 'tahap_satu':
                $tahap = $sintaks->sintaksTahapSatu;
                if (!$tahap) {
                    if ($request->has('orientasi_masalah') || $request->has('rumusan_masalah') || 
                        $request->hasFile('file_indikator_masalah') || $request->hasFile('file_hasil_analisis')) {
                        $tahap = new \App\Models\SintaksTahapSatu();
                        $tahap->sintaks_id = $sintaks->id;
                        $tahap->status_validasi = 'pending';
                        
                        if ($request->has('orientasi_masalah')) {
                            $tahap->orientasi_masalah = $request->orientasi_masalah;
                        }
                        if ($request->has('rumusan_masalah')) {
                            $tahap->rumusan_masalah = $request->rumusan_masalah;
                        }
                        
                        $tahap->save();
                        
                        $this->processFileUploads($request, $tahap);
                        
                        return redirect()->back()->with('success', 'Data tahap 1 berhasil disimpan.');
                    }
                    return redirect()->back()->with('error', 'Tahap 1 belum diisi oleh kelompok.');
                }
                
                $dataUpdated = false;
                
                if ($request->has('orientasi_masalah')) {
                    $tahap->orientasi_masalah = $request->orientasi_masalah;
                    $dataUpdated = true;
                }
                
                if ($request->has('rumusan_masalah')) {
                    $tahap->rumusan_masalah = $request->rumusan_masalah;
                    $dataUpdated = true;
                }
                
                $filesUpdated = $this->processFileUploads($request, $tahap);
                
                if ($dataUpdated || $filesUpdated) {
                    $tahap->save();
                    return redirect()->back()->with('success', 'Data tahap 1 berhasil diperbarui.');
                }
                break;
            case 'tahap_dua':
                $tahap = $sintaks->sintaksTahapDua;
                if (!$tahap) {
                    if ($request->has('deskripsi_rancangan') || $request->hasFile('file_rancangan')) {
                        $tahap = new \App\Models\SintaksTahapDua();
                        $tahap->sintaks_id = $sintaks->id;
                        $tahap->status_validasi = 'pending';
                        
                        if ($request->has('deskripsi_rancangan')) {
                            $tahap->deskripsi_rancangan = $request->deskripsi_rancangan;
                        }
                        
                        $tahap->save();
                        
                        $this->processFileUploads($request, $tahap);
                        
                        return redirect()->back()->with('success', 'Data tahap 2 berhasil disimpan.');
                    }
                    return redirect()->back()->with('error', 'Tahap 2 belum diisi oleh kelompok.');
                }
                
                $dataUpdated = false;
                
                if ($request->has('deskripsi_rancangan')) {
                    $tahap->deskripsi_rancangan = $request->deskripsi_rancangan;
                    $dataUpdated = true;
                }
                
                $filesUpdated = $this->processFileUploads($request, $tahap);
                
                if ($dataUpdated || $filesUpdated) {
                    $tahap->save();
                    return redirect()->back()->with('success', 'Data tahap 2 berhasil diperbarui.');
                }
                break;
            case 'tahap_tiga':
                $tahap = $sintaks->sintaksTahapTiga;
                if (!$tahap) {
                    return redirect()->back()->with('error', 'Tahap 3 belum diisi oleh kelompok.');
                }
                
                // Update tanggal mulai dan selesai jika ada
                if ($request->has('tanggal_mulai')) {
                    $tahap->tanggal_mulai = $request->tanggal_mulai;
                }
                
                if ($request->has('tanggal_selesai')) {
                    $tahap->tanggal_selesai = $request->tanggal_selesai;
                }
                
                break;
            case 'tahap_empat':
                $tahap = $sintaks->sintaksTahapEmpat;
                if (!$tahap) {
                    return redirect()->back()->with('error', 'Tahap 4 belum diisi oleh kelompok.');
                }
                break;
            case 'tahap_lima':
                $tahap = $sintaks->sintaksTahapLima;
                if (!$tahap) {
                    if ($request->has('deskripsi_hasil') || $request->hasFile('file_hasil_karya')) {
                        $tahap = new \App\Models\SintaksTahapLima();
                        $tahap->sintaks_id = $sintaks->id;
                        $tahap->status_validasi = 'pending';
                        $tahap->status = 'proses';
                        
                        if ($request->has('deskripsi_hasil')) {
                            $tahap->deskripsi_hasil = $request->deskripsi_hasil;
                        }
                        
                        $tahap->save();
                        
                        $this->processFileUploads($request, $tahap);
                        
                        return redirect()->back()->with('success', 'Data tahap 5 berhasil disimpan.');
                    }
                    return redirect()->back()->with('error', 'Tahap 5 belum diisi oleh kelompok.');
                }
                
                $dataUpdated = false;
                
                if ($request->has('deskripsi_hasil')) {
                    $tahap->deskripsi_hasil = $request->deskripsi_hasil;
                    $dataUpdated = true;
                }
                
                $filesUpdated = $this->processFileUploads($request, $tahap);
                
                if ($dataUpdated || $filesUpdated) {
                    $tahap->save();
                    return redirect()->back()->with('success', 'Data tahap 5 berhasil diperbarui.');
                }
                break;
            case 'tahap_enam':
                $tahap = $sintaks->sintaksTahapEnam;
                if (!$tahap) {
                    if ($request->has('link_presentasi') || $request->has('jadwal_presentasi') || 
                        $request->has('catatan_presentasi')) {
                        $tahap = new \App\Models\SintaksTahapEnam();
                        $tahap->sintaks_id = $sintaks->id;
                        $tahap->status_validasi = 'pending';
                        $tahap->status = 'proses';
                        
                        if ($request->has('link_presentasi')) {
                            $tahap->link_presentasi = $request->link_presentasi;
                        }
                        
                        if ($request->has('jadwal_presentasi')) {
                            $tahap->jadwal_presentasi = $request->jadwal_presentasi;
                        }
                        
                        if ($request->has('catatan_presentasi')) {
                            $tahap->catatan_presentasi = $request->catatan_presentasi;
                        }
                        
                        $tahap->save();
                        
                        
                        return redirect()->back()->with('success', 'Data tahap 6 berhasil disimpan.');
                    }
                    return redirect()->back()->with('error', 'Tahap 6 belum diisi oleh kelompok.');
                }
                
                $dataUpdated = false;
                
                if ($request->has('link_presentasi')) {
                    $tahap->link_presentasi = $request->link_presentasi;
                    $dataUpdated = true;
                }
                
                if ($request->has('jadwal_presentasi')) {
                    $tahap->jadwal_presentasi = $request->jadwal_presentasi;
                    $dataUpdated = true;
                }
                
                if ($request->has('catatan_presentasi')) {
                    $tahap->catatan_presentasi = $request->catatan_presentasi;
                    $dataUpdated = true;
                }
                
                
                if ($dataUpdated) {
                    $tahap->save();
                    return redirect()->back()->with('success', 'Data tahap 6 berhasil diperbarui.');
                }
                break;
            case 'tahap_tuju':
                $tahap = $sintaks->sintaksTahapTuju;
                if (!$tahap) {
                    $tahap = new \App\Models\SintaksTahapTuju();
                    $tahap->sintaks_id = $sintaks->id;
                }
                
                $tahap->status_validasi = $request->status_validasi;
                $tahap->feedback_guru = $request->feedback_guru;
                $tahap->save();
                
                // Jika validasi menjadi valid, update status tahap menjadi selesai
                if ($request->status_validasi === 'valid') {
                    $tahap->status = 'selesai';
                    $tahap->save();
                }
                
                return redirect()->back()->with('success', 'Status validasi Tahap 7 berhasil diperbarui.');
                break;
            case 'tahap_delapan':
                $tahap = $sintaks->sintaksTahapDelapan;
                if (!$tahap) {
                    return redirect()->back()->with('error', 'Tahap 8 belum diisi oleh kelompok.');
                }
                
                $tahap->status_validasi = $request->status_validasi;
                $tahap->feedback_guru = $request->feedback_guru;
                $tahap->save();
                
                // Jika validasi menjadi valid, update status tahap menjadi selesai
                if ($request->status_validasi === 'valid') {
                    $tahap->status = 'selesai';
                    $tahap->save();
                }
                
                return redirect()->back()->with('success', 'Status validasi Tahap 8 berhasil diperbarui.');
                break;
            default:
                return redirect()->back()->with('error', 'Tahap tidak valid.');
        }
        
        $tahap->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        // Jika semua tahap sudah valid, update status validasi sintaks utama
        if ($request->status_validasi === 'valid') {
            $this->checkAllTahapValid($sintaks);
        }

        return redirect()->back()->with('success', 'Status validasi berhasil diperbarui.');
    }

    /**
     * Memeriksa apakah semua tahap sudah valid untuk mengupdate status validasi sintaks utama
     */
    private function checkAllTahapValid(SintaksBaru $sintaks)
    {
        // Array untuk menyimpan status validasi setiap tahap
        $tahapStatus = [
            $sintaks->sintaksTahapSatu ? $sintaks->sintaksTahapSatu->status_validasi : null,
            $sintaks->sintaksTahapDua ? $sintaks->sintaksTahapDua->status_validasi : null,
            $sintaks->sintaksTahapTiga ? $sintaks->sintaksTahapTiga->status_validasi : null,
            $sintaks->sintaksTahapEmpat ? $sintaks->sintaksTahapEmpat->status_validasi : null,
            $sintaks->sintaksTahapLima ? $sintaks->sintaksTahapLima->status_validasi : null,
            $sintaks->sintaksTahapEnam ? $sintaks->sintaksTahapEnam->status_validasi : null,
            $sintaks->sintaksTahapTuju ? $sintaks->sintaksTahapTuju->status_validasi : null,
            $sintaks->sintaksTahapDelapan ? $sintaks->sintaksTahapDelapan->status_validasi : null,
        ];
        
        // Filter hanya tahap yang sudah ada (tidak null)
        $existingTahap = array_filter($tahapStatus, function($status) {
            return $status !== null;
        });
        
        // Jika semua tahap yang ada sudah valid, set sintaks utama juga valid
        if (count($existingTahap) > 0 && count(array_filter($existingTahap, function($status) {
            return $status === 'valid';
        })) === count($existingTahap)) {
            $sintaks->update(['status_validasi' => 'valid']);
        }
    }

    /**
     * Memposting orientasi masalah ke seluruh kelompok pada suatu materi
     *
     * @param Request $request
     * @param Materi $materi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postOrientasiMasalah(Request $request, Materi $materi)
    {
        // Validasi input
        $request->validate([
            'orientasi_masalah' => 'required|string',
        ]);

        // Cek apakah PJBL Sintaks aktif untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('admin.pjbl.sintaks.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini.');
        }

        // Dapatkan semua kelompok yang terkait dengan materi ini
        $kelompoks = Kelompok::whereHas('sintaks', function($query) use ($materi) {
            $query->where('materi_id', $materi->id);
        })->get();

        // Jika tidak ada kelompok, buat sintaks baru untuk setiap kelompok
        if ($kelompoks->isEmpty()) {
            $kelompoks = Kelompok::all(); // Ambil semua kelompok jika tidak ada yang terkait
        }

        // Simpan orientasi masalah ke setiap kelompok
        $berhasilCount = 0;
        foreach ($kelompoks as $kelompok) {
            // Dapatkan atau buat sintaks baru untuk kelompok ini
            $sintaks = SintaksBaru::firstOrCreate([
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
            ], [
                'status_validasi' => 'pending'
            ]);

            // Cek apakah tahap satu sudah ada
            $tahapSatu = $sintaks->sintaksTahapSatu;
            if (!$tahapSatu) {
                // Buat tahap satu baru jika belum ada
                $tahapSatu = new SintaksTahapSatu([
                    'sintaks_id' => $sintaks->id,
                    'orientasi_masalah' => $request->orientasi_masalah,
                    'status_validasi' => 'invalid', // Otomatis divalidasi karena dibuat oleh guru
                    'feedback_guru' => 'Orientasi masalah diposting oleh guru'
                ]);


                $tahapSatu->save();
                $berhasilCount++;
            } else {
                // Update tahap satu jika sudah ada
                $tahapSatu->orientasi_masalah = $request->orientasi_masalah;
                $tahapSatu->status_validasi = 'invalid';
                
                $tahapSatu->save();
                $berhasilCount++;
            }
        }

        return redirect()->back()->with('success', "Orientasi masalah berhasil diposting ke $berhasilCount kelompok.");
    }

    /**
     * Memberikan nilai untuk semua anggota kelompok secara batch/sekaligus
     *
     * @param Request $request
     * @param Materi $materi
     * @param Kelompok $kelompok
     * @return \Illuminate\Http\RedirectResponse
     */
    public function beriNilaiBatch(Request $request, Materi $materi, Kelompok $kelompok)
    {
        // Cek apakah PJBL Sintaks aktif untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('admin.pjbl.sintaks.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini.');
        }

        // Validasi input
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.user_id' => 'required|exists:users,id',
            'nilai.*.pemahaman' => 'required|integer|min:0|max:100',
            'nilai.*.implementasi' => 'required|integer|min:0|max:100',
            'nilai.*.kreativitas' => 'required|integer|min:0|max:100',
            'nilai.*.presentasi' => 'required|integer|min:0|max:100',
            'nilai.*.dokumentasi' => 'required|integer|min:0|max:100',
            'nilai.*.total' => 'required|numeric|min:0|max:100',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                           ->where('kelompok_id', $kelompok->id)
                           ->firstOrFail();
        
        // Ambil atau buat SintaksTahapTuju
        $tahapTuju = $sintaks->sintaksTahapTuju;
        if (!$tahapTuju) {
            $tahapTuju = SintaksTahapTuju::create([
                'sintaks_id' => $sintaks->id,
                'status_validasi' => 'valid',
                'feedback_guru' => $request->feedback_guru,
                'status' => 'selesai'
            ]);
        } else {
            $tahapTuju->update([
                'status_validasi' => 'valid',
                'feedback_guru' => $request->feedback_guru,
                'status' => 'selesai'
            ]);
        }

        // Simpan nilai untuk setiap anggota kelompok
        $totalNilaiKelompok = 0;
        $jumlahAnggota = 0;

        foreach ($request->nilai as $nilai) {
            $userId = $nilai['user_id'];
            $totalNilai = $nilai['total'];
            $totalNilaiKelompok += $totalNilai;
            $jumlahAnggota++;
            
            // Siapkan nilai kriteria dengan format yang sesuai
            $nilaiKriteria = [
                'kriteria' => [
                    [
                        'nama' => 'Pemahaman Konsep',
                        'nilai' => $nilai['pemahaman'],
                        'bobot' => 3
                    ],
                    [
                        'nama' => 'Implementasi',
                        'nilai' => $nilai['implementasi'],
                        'bobot' => 4
                    ],
                    [
                        'nama' => 'Kreativitas',
                        'nilai' => $nilai['kreativitas'],
                        'bobot' => 2
                    ],
                    [
                        'nama' => 'Presentasi',
                        'nilai' => $nilai['presentasi'],
                        'bobot' => 2
                    ],
                    [
                        'nama' => 'Dokumentasi',
                        'nilai' => $nilai['dokumentasi'],
                        'bobot' => 1
                    ]
                ]
            ];

            // Simpan nilai individu
            SintaksTahapTujuNilai::updateOrCreate(
                [
                    'sintaks_penilaian_id' => $tahapTuju->id,
                    'user_id' => $userId
                ],
                [
                    'nilai_kriteria' => $nilaiKriteria,
                    'total_nilai_individu' => $totalNilai
                ]
            );
        }

        // Update total nilai di sintaks utama
        if ($jumlahAnggota > 0) {
            $sintaks->update(['total_nilai' => round($totalNilaiKelompok / $jumlahAnggota)]);
        }

        return redirect()->back()->with('success', "Nilai berhasil disimpan untuk semua anggota kelompok.");
    }

    /**
     * Menangani upload file untuk tahap satu
     * 
     * @param Request $request
     * @param \App\Models\SintaksTahapSatu $tahap
     * @return bool
     */
    private function processFileUploads(Request $request, $tahap)
    {
        $filesUpdated = false;
        
        // Upload file indikator masalah jika ada
        if ($request->hasFile('file_indikator_masalah')) {
            // Hapus file lama jika ada
            if ($tahap->file_indikator_masalah && Storage::disk('public')->exists($tahap->file_indikator_masalah)) {
                Storage::disk('public')->delete($tahap->file_indikator_masalah);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_indikator_masalah')->store('sintaks/tahap1', 'public');
            $tahap->file_indikator_masalah = $filePath;
            $filesUpdated = true;
        }
        
        // Upload file hasil analisis jika ada
        if ($request->hasFile('file_hasil_analisis')) {
            // Hapus file lama jika ada
            if ($tahap->file_hasil_analisis && Storage::disk('public')->exists($tahap->file_hasil_analisis)) {
                Storage::disk('public')->delete($tahap->file_hasil_analisis);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_hasil_analisis')->store('sintaks/tahap1', 'public');
            $tahap->file_hasil_analisis = $filePath;
            $filesUpdated = true;
        }
        
        // Upload file rancangan jika ada (untuk tahap 2)
        if ($request->hasFile('file_rancangan')) {
            // Hapus file lama jika ada
            if (isset($tahap->file_rancangan) && $tahap->file_rancangan && Storage::disk('public')->exists($tahap->file_rancangan)) {
                Storage::disk('public')->delete($tahap->file_rancangan);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_rancangan')->store('sintaks/tahap2', 'public');
            $tahap->file_rancangan = $filePath;
            $filesUpdated = true;
        }
        
        // Upload file hasil karya jika ada (untuk tahap 5)
        if ($request->hasFile('file_hasil_karya')) {
            // Hapus file lama jika ada
            if (isset($tahap->file_hasil_karya) && $tahap->file_hasil_karya && Storage::disk('public')->exists($tahap->file_hasil_karya)) {
                Storage::disk('public')->delete($tahap->file_hasil_karya);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_hasil_karya')->store('sintaks/tahap5', 'public');
            $tahap->file_hasil_karya = $filePath;
            $filesUpdated = true;
        }
        
        return $filesUpdated;
    }
}