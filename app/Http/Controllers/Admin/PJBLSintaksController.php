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
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapLima = $sintaks->sintaksTahapLima;
        
        if (!$tahapLima) {
            return redirect()->back()->with('error', 'Tahap 5 belum diisi oleh kelompok.');
        }
        
        $tahapLima->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 5 berhasil diperbarui.');
    }

    // Validasi untuk tahap 6 (Presentasi Proyek)
    public function validasiTahapEnam(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        $sintaks = SintaksBaru::where('materi_id', $materi->id)
                            ->where('kelompok_id', $kelompok->id)
                            ->firstOrFail();
        
        $tahapEnam = $sintaks->sintaksTahapEnam;
        
        if (!$tahapEnam) {
            return redirect()->back()->with('error', 'Tahap 6 belum diisi oleh kelompok.');
        }
        
        $tahapEnam->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

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
            'nilai_kriteria.*.nama' => 'required|string',
            'nilai_kriteria.*.nilai' => 'required|integer|min:0|max:100',
            'nilai_kriteria.*.bobot' => 'required|integer|min:0|max:100',
            'total_nilai_individu' => 'required|integer|min:0|max:100',
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
                'feedback_guru' => $request->feedback_guru
            ]);
        } else {
            $tahapTuju->update([
                'status_validasi' => 'valid',
                'feedback_guru' => $request->feedback_guru
            ]);
        }

        // Simpan nilai individu
        SintaksTahapTujuNilai::updateOrCreate(
            [
                'sintaks_penilaian_id' => $tahapTuju->id,
                'user_id' => $request->user_id
            ],
            [
                'nilai_kriteria' => json_encode($request->nilai_kriteria),
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
        
        $tahapDelapan->update([
            'status_validasi' => $request->status_validasi,
            'feedback_guru' => $request->feedback_guru,
        ]);

        return redirect()->back()->with('success', 'Status validasi Tahap 8 berhasil diperbarui.');
    }

    // Metode wrapper untuk validasi semua tahap
    public function validasiTahap(Request $request, Materi $materi, Kelompok $kelompok)
    {
        $request->validate([
            'tahap' => 'required|in:tahap_1,tahap_2,tahap_3,tahap_4,tahap_5,tahap_6,tahap_8',
            'status_validasi' => 'required|in:valid,invalid,pending',
            'feedback_guru' => 'nullable|string',
        ]);

        switch ($request->tahap) {
            case 'tahap_1':
                return $this->validasiTahapSatu($request, $materi, $kelompok);
            case 'tahap_2':
                return $this->validasiTahapDua($request, $materi, $kelompok);
            case 'tahap_3':
                return $this->validasiTahapTiga($request, $materi, $kelompok);
            case 'tahap_4':
                return $this->validasiTahapEmpat($request, $materi, $kelompok);
            case 'tahap_5':
                return $this->validasiTahapLima($request, $materi, $kelompok);
            case 'tahap_6':
                return $this->validasiTahapEnam($request, $materi, $kelompok);
            case 'tahap_8':
                return $this->validasiTahapDelapan($request, $materi, $kelompok);
            default:
                return redirect()->back()->with('error', 'Tahap tidak valid.');
        }
    }
}