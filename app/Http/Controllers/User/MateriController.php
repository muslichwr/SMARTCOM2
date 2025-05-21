<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SelesaikanMateriRequest;
use App\Models\Answer;
use App\Models\Anggota;
use App\Models\Bab;
use App\Models\BabAttempt;
use App\Models\Latihan;
use App\Models\LatihanAnswer;
use App\Models\LatihanAttempt;
use App\Models\Kelompok;
use App\Models\Materi;
use App\Models\PostTestAnswer;
use App\Models\PostTestAttempt;
use App\Models\PrePost;
use App\Models\PrePostTest;
use App\Models\PreTestAnswer;
use App\Models\PreTestAttempt;
use App\Models\Sintaks;
use App\Models\Test;
use Carbon\Carbon;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $materis = Materi::withCount(['babs', 'latihans'])->whereNotIn('judul', ['Pre Test', 'Post Test'])->orderBy('id', 'ASC')->paginate(5);

        // * Progress Bar
        foreach ($materis as $materi) {
            $materi->total = $materi->babs_count + $materi->latihans_count;

            // Mengambil status babs_attempts dengan status = 1
            $babsAttemptsStatus = DB::table('babs_attempt')
                ->join('babs', 'babs_attempt.bab_id', '=', 'babs.id')
                ->where('babs.materi_id', $materi->id)
                ->where('babs_attempt.status', 1)
                ->where('babs_attempt.user_id', $userId)
                ->select('babs_attempt.status')
                ->get();

            // Mengambil status latihans_attempts dengan status = 2 dan parameter updated_at yang terbaru
            $latihansAttemptsStatus = DB::table('latihans_attempt')
                ->join('latihans', 'latihans_attempt.latihan_id', '=', 'latihans.id')
                ->where('latihans.materi_id', $materi->id)
                ->where('latihans_attempt.status', 2)
                ->where('latihans_attempt.user_id', $userId)
                ->orderByDesc('latihans_attempt.updated_at')
                ->select('latihans_attempt.status')
                ->first();

            // dd($babsAttemptsStatus);

            $materi->babs_attempt_status = $babsAttemptsStatus->isNotEmpty() ? $babsAttemptsStatus : null;

            $materi->latihans_attempt_status = $latihansAttemptsStatus ? $latihansAttemptsStatus->status : null;
        }

        return view('frontend.user.materi.index', ['materis' => $materis]);
    }

    public function tampilkanSintaks($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
        
        // Ambil atau buat sintaks untuk kelompok dan materi ini
        $sintaks = \App\Models\SintaksBaru::firstOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id
            ],
            [
                'status_validasi' => 'pending'
            ]
        );
        
        // Load semua tahapan
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
        
        return view('frontend.user.sintaks.index', compact('materi', 'sintaks', 'kelompok'));
    }

    public function tahap7($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain dengan pesan error
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapEnam'])
                                       ->firstOrFail();
        
        // Pastikan tahap 6 sudah valid sebelum lanjut ke tahap 7
        if (!$sintaks->sintaksTahapEnam || $sintaks->sintaksTahapEnam->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 6 terlebih dahulu.');
        }
        
        // Ambil data tahap tuju jika sudah ada
        $tahapTuju = $sintaks->sintaksTahapTuju;
        
        // Jika belum ada, buat yang baru
        if (!$tahapTuju) {
            $tahapTuju = new \App\Models\SintaksTahapTuju();
            $tahapTuju->sintaks_id = $sintaks->id;
            $tahapTuju->status_validasi = 'pending';
            $tahapTuju->save();
            
            return redirect()->route('user.materi.tahap7', $materi->slug)
                            ->with('success', 'Silakan meminta penilaian dari guru.');
        }
        
        // Load nilai individu jika ada
        $tahapTuju->load('nilaiIndividu');
    
        // Kirim data ke view
        return view('frontend.user.sintaks.tahap7', compact('materi', 'kelompok', 'sintaks', 'tahapTuju'));
    }

    public function mintaPenilaian(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain dengan pesan error
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks dan data tahap tuju
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapTuju')
                                       ->firstOrFail();
        
        // Pastikan tahap tuju sudah ada
        if (!$sintaks->sintaksTahapTuju) {
            return redirect()->route('user.materi.tahap7', ['slug' => $slug])
                            ->with('error', 'Tahap 7 belum dibuat.');
        }
    
        // Update status validasi menjadi 'pending'
        $sintaks->sintaksTahapTuju->status_validasi = 'pending';
        $sintaks->sintaksTahapTuju->save();
    
        return redirect()->route('user.materi.tahap7', $materi->slug)
                        ->with('success', 'Permintaan penilaian telah dikirim.');
    }


    public function tahap6($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapLima'])
                                       ->firstOrFail();
        
        // Pastikan tahap 5 sudah valid sebelum lanjut ke tahap 6
        if (!$sintaks->sintaksTahapLima || $sintaks->sintaksTahapLima->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 5 terlebih dahulu.');
        }
        
        // Ambil data tahap enam jika sudah ada
        $tahapEnam = $sintaks->sintaksTahapEnam;
        
        // Jika belum ada, buat yang baru
        if (!$tahapEnam) {
            $tahapEnam = new \App\Models\SintaksTahapEnam();
            $tahapEnam->sintaks_id = $sintaks->id;
            $tahapEnam->status_validasi = 'pending';
            $tahapEnam->save();
            
            return redirect()->route('user.materi.tahap6', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 6.');
        }

        return view('frontend.user.sintaks.tahap6', compact('materi', 'kelompok', 'sintaks', 'tahapEnam'));
    }

    public function simpanTahap6(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'link_presentasi' => 'required|url',
            'tanggal_presentasi' => 'required|date',
            'file_presentasi' => 'nullable|file|mimes:pdf,ppt,pptx|max:20480',
            'catatan_presentasi' => 'nullable|string',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapEnam')
                                       ->firstOrFail();
        
        // Pastikan tahap enam sudah ada
        if (!$sintaks->sintaksTahapEnam) {
            return redirect()->route('user.materi.tahap6', ['slug' => $slug])
                            ->with('error', 'Tahap 6 belum dibuat.');
        }
        
        // Update data tahap enam
        $tahapEnam = $sintaks->sintaksTahapEnam;
        $tahapEnam->link_presentasi = $request->link_presentasi;
        $tahapEnam->tanggal_presentasi = $request->tanggal_presentasi;
        $tahapEnam->catatan_presentasi = $request->catatan_presentasi;
        $tahapEnam->status_validasi = 'pending';
        
        // Upload file jika ada
        if ($request->hasFile('file_presentasi')) {
            // Hapus file lama jika ada
            if ($tahapEnam->file_presentasi && Storage::disk('public')->exists($tahapEnam->file_presentasi)) {
                Storage::disk('public')->delete($tahapEnam->file_presentasi);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_presentasi')->store('sintaks/tahap6', 'public');
            $tahapEnam->file_presentasi = $filePath;
        }
        
        $tahapEnam->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 6 berhasil disimpan');
    }

    public function tahap5($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapEmpat'])
                                       ->firstOrFail();
        
        // Pastikan tahap 4 sudah valid sebelum lanjut ke tahap 5
        if (!$sintaks->sintaksTahapEmpat || $sintaks->sintaksTahapEmpat->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 4 terlebih dahulu.');
        }
        
        // Ambil data tahap lima jika sudah ada
        $tahapLima = $sintaks->sintaksTahapLima;
        
        // Jika belum ada, buat yang baru
        if (!$tahapLima) {
            $tahapLima = new \App\Models\SintaksTahapLima();
            $tahapLima->sintaks_id = $sintaks->id;
            $tahapLima->status_validasi = 'pending';
            
            // Ambil tugas yang sudah completed dari tahap 4
            $completedTasks = \App\Models\SintaksTahapEmpatTugas::where('sintaks_tahap_empat_id', $sintaks->sintaksTahapEmpat->id)
                                                             ->where('status', 'completed')
                                                             ->with('user')
                                                             ->get();
            
            // Inisialisasi progress karya
            $progressKarya = [];
            foreach ($completedTasks as $task) {
                $progressKarya[] = [
                    'tugas' => $task->deskripsi_tugas,
                    'user_id' => $task->user_id,
                    'nama_anggota' => $task->user->name,
                    'status' => 'in_progress',
                    'file_tugas' => null
                ];
            }
            
            // Simpan progress karya dalam format JSON
            $tahapLima->progress_karya = json_encode($progressKarya);
            $tahapLima->save();
            
            return redirect()->route('user.materi.tahap5', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 5.');
        }

        return view('frontend.user.sintaks.tahap5', compact('materi', 'kelompok', 'sintaks', 'tahapLima'));
    }

    public function simpanTahap5(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'deskripsi_karya' => 'required|string',
            'file_karya' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip|max:20480',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapLima')
                                       ->firstOrFail();
        
        // Pastikan tahap lima sudah ada
        if (!$sintaks->sintaksTahapLima) {
            return redirect()->route('user.materi.tahap5', ['slug' => $slug])
                            ->with('error', 'Tahap 5 belum dibuat.');
        }
        
        // Update data tahap lima
        $tahapLima = $sintaks->sintaksTahapLima;
        $tahapLima->deskripsi_karya = $request->deskripsi_karya;
        
        // Upload file jika ada
        if ($request->hasFile('file_karya')) {
            // Hapus file lama jika ada
            if ($tahapLima->file_karya && Storage::disk('public')->exists($tahapLima->file_karya)) {
                Storage::disk('public')->delete($tahapLima->file_karya);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_karya')->store('sintaks/tahap5', 'public');
            $tahapLima->file_karya = $filePath;
        }
        
        // Update status validasi
        $tahapLima->status_validasi = 'pending';
        $tahapLima->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 5 berhasil disimpan');
    }
    
    public function updateProgressKarya(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'index' => 'required|integer|min:0',
            'status' => 'required|in:in_progress,completed',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapLima')
                                       ->firstOrFail();
        
        // Pastikan tahap lima sudah ada
        if (!$sintaks->sintaksTahapLima) {
            return redirect()->route('user.materi.tahap5', ['slug' => $slug])
                            ->with('error', 'Tahap 5 belum dibuat.');
        }
        
        // Ambil dan update progress karya
        $tahapLima = $sintaks->sintaksTahapLima;
        $progressKarya = json_decode($tahapLima->progress_karya, true);
        
        // Pastikan index valid
        if (!isset($progressKarya[$request->index])) {
            return redirect()->route('user.materi.tahap5', ['slug' => $slug])
                            ->with('error', 'Index progress karya tidak valid.');
        }
        
        // Pastikan hanya user yang bersangkutan yang bisa update
        if ($progressKarya[$request->index]['user_id'] != auth()->id()) {
            return redirect()->route('user.materi.tahap5', ['slug' => $slug])
                            ->with('error', 'Anda tidak berhak mengubah progress ini.');
        }
        
        // Update status
        $progressKarya[$request->index]['status'] = $request->status;
        
        // Upload file jika ada
        if ($request->hasFile('file_tugas')) {
            // Hapus file lama jika ada
            $oldFilePath = $progressKarya[$request->index]['file_tugas'] ?? null;
            if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_tugas')->store('sintaks/tahap5/progress', 'public');
            $progressKarya[$request->index]['file_tugas'] = $filePath;
        }
        
        // Simpan kembali progress karya
        $tahapLima->progress_karya = json_encode($progressKarya);
        $tahapLima->save();
        
        return redirect()->route('user.materi.tahap5', ['slug' => $slug])
                        ->with('success', 'Progress karya berhasil diperbarui');
    }

    public function tahap4($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapTiga'])
                                       ->firstOrFail();
        
        // Pastikan tahap 3 sudah valid sebelum lanjut ke tahap 4
        if (!$sintaks->sintaksTahapTiga || $sintaks->sintaksTahapTiga->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 3 terlebih dahulu.');
        }
        
        // Ambil data tahap empat jika sudah ada
        $tahapEmpat = $sintaks->sintaksTahapEmpat;
        
        // Jika belum ada, buat yang baru
        if (!$tahapEmpat) {
            $tahapEmpat = new \App\Models\SintaksTahapEmpat();
            $tahapEmpat->sintaks_id = $sintaks->id;
            $tahapEmpat->status_validasi = 'pending';
            $tahapEmpat->save();
            
            // Buat tugas berdasarkan tugas_anggota di tahap 3
            $tugasAnggota = json_decode($sintaks->sintaksTahapTiga->tugas_anggota, true);
            $anggotaKelompok = $kelompok->anggotas;
            
            if (is_array($tugasAnggota) && count($anggotaKelompok) > 0) {
                foreach ($tugasAnggota as $index => $tugas) {
                    if (isset($anggotaKelompok[$index])) {
                        $task = new \App\Models\SintaksTahapEmpatTugas();
                        $task->sintaks_tahap_empat_id = $tahapEmpat->id;
                        $task->user_id = $anggotaKelompok[$index]->user_id;
                        $task->deskripsi_tugas = $tugas;
                        $task->status = 'pending';
                        $task->deadline = date('Y-m-d', strtotime($sintaks->sintaksTahapTiga->jadwal_selesai));
                        $task->save();
                    }
                }
            }
            
            return redirect()->route('user.materi.tahap4', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 4.');
        }
        
        // Load tugas-tugas dengan relasi user
        $tahapEmpat->load('tasks.user');

        return view('frontend.user.sintaks.tahap4', compact('materi', 'kelompok', 'sintaks', 'tahapEmpat'));
    }

    public function simpanTahap4(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'task_id' => 'required|exists:sintaks_tahap_empat_tugas,id',
            'status' => 'required|in:pending,in_progress,completed',
            'catatan' => 'nullable|string',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapEmpat')
                                       ->firstOrFail();
        
        // Pastikan tahap empat sudah ada
        if (!$sintaks->sintaksTahapEmpat) {
            return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                            ->with('error', 'Tahap 4 belum dibuat.');
        }
        
        // Ambil tugas berdasarkan id
        $task = \App\Models\SintaksTahapEmpatTugas::where('id', $request->task_id)
                                                ->where('sintaks_tahap_empat_id', $sintaks->sintaksTahapEmpat->id)
                                                ->firstOrFail();
        
        // Pastikan hanya pemilik tugas yang bisa mengubah
        if ($task->user_id != auth()->id()) {
            return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                            ->with('error', 'Anda tidak berhak mengubah tugas ini.');
        }
        
        // Update status tugas
        $task->status = $request->status;
        $task->catatan = $request->catatan;
        
        // Upload file jika ada
        if ($request->hasFile('file_tugas')) {
            // Hapus file lama jika ada
            if ($task->file_tugas && Storage::disk('public')->exists($task->file_tugas)) {
                Storage::disk('public')->delete($task->file_tugas);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_tugas')->store('sintaks/tahap4', 'public');
            $task->file_tugas = $filePath;
        }
        
        $task->save();
        
        // Periksa apakah semua tugas telah selesai
        $allTasksCompleted = \App\Models\SintaksTahapEmpatTugas::where('sintaks_tahap_empat_id', $sintaks->sintaksTahapEmpat->id)
                                                            ->where('status', '!=', 'completed')
                                                            ->count() === 0;
        
        // Update status tahap empat jika semua tugas selesai
        if ($allTasksCompleted) {
            $sintaks->sintaksTahapEmpat->status = 'completed';
            $sintaks->sintaksTahapEmpat->save();
        }
    
        return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                        ->with('success', 'Tugas berhasil diperbarui');
    }
    
    public function tambahTugasTahap4(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'deskripsi_tugas' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapEmpat')
                                       ->firstOrFail();
        
        // Pastikan tahap empat sudah ada
        if (!$sintaks->sintaksTahapEmpat) {
            return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                            ->with('error', 'Tahap 4 belum dibuat.');
        }
        
        // Pastikan user_id adalah anggota kelompok
        $isAnggota = $kelompok->anggotas()->whereHas('user', function($query) use ($request) {
            $query->where('id', $request->user_id);
        })->exists();
        
        if (!$isAnggota) {
            return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                            ->with('error', 'User yang dipilih bukan anggota kelompok.');
        }
        
        // Buat tugas baru
        $task = new \App\Models\SintaksTahapEmpatTugas();
        $task->sintaks_tahap_empat_id = $sintaks->sintaksTahapEmpat->id;
        $task->user_id = $request->user_id;
        $task->deskripsi_tugas = $request->deskripsi_tugas;
        $task->status = 'pending';
        $task->deadline = $request->deadline;
        $task->save();
    
        return redirect()->route('user.materi.tahap4', ['slug' => $slug])
                        ->with('success', 'Tugas berhasil ditambahkan');
    }

    public function tahap3($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain dengan pesan error
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil anggota kelompok
        $anggotaKelompok = $kelompok->anggotas;

        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapSatu', 'sintaksTahapDua'])
                                       ->firstOrFail();
        
        // Pastikan tahap 2 sudah valid sebelum lanjut ke tahap 3
        if (!$sintaks->sintaksTahapDua || $sintaks->sintaksTahapDua->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 2 terlebih dahulu.');
        }
        
        // Ambil data tahap tiga jika sudah ada
        $tahapTiga = $sintaks->sintaksTahapTiga;
        
        // Jika belum ada, buat yang baru
        if (!$tahapTiga) {
            $tahapTiga = new \App\Models\SintaksTahapTiga();
            $tahapTiga->sintaks_id = $sintaks->id;
            $tahapTiga->status_validasi = 'pending';
            $tahapTiga->save();
            
            return redirect()->route('user.materi.tahap3', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 3.');
        }

        return view('frontend.user.sintaks.tahap3', compact('materi', 'kelompok', 'sintaks', 'tahapTiga', 'anggotaKelompok'));
    }

    public function simpanTahap3(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'jadwal_mulai' => 'required|date',
            'jadwal_selesai' => 'required|date|after_or_equal:jadwal_mulai',
            'tugas_anggota' => 'required|array',
            'tugas_anggota.*' => 'required|string',
            'file_jadwal' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapDua')
                                       ->firstOrFail();
        
        // Cek apakah tahap 2 sudah divalidasi
        if (!$sintaks->sintaksTahapDua || $sintaks->sintaksTahapDua->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 2 terlebih dahulu.');
        }
        
        // Ambil atau buat tahap tiga
        $tahapTiga = $sintaks->sintaksTahapTiga;
        if (!$tahapTiga) {
            $tahapTiga = new \App\Models\SintaksTahapTiga();
            $tahapTiga->sintaks_id = $sintaks->id;
        }
        
        // Update data tahap tiga
        $tahapTiga->jadwal_mulai = $request->jadwal_mulai;
        $tahapTiga->jadwal_selesai = $request->jadwal_selesai;
        $tahapTiga->tugas_anggota = json_encode($request->tugas_anggota);
        $tahapTiga->status_validasi = 'pending';
        
        // Upload file jika ada
        if ($request->hasFile('file_jadwal')) {
            // Hapus file lama jika ada
            if ($tahapTiga->file_jadwal && Storage::disk('public')->exists($tahapTiga->file_jadwal)) {
                Storage::disk('public')->delete($tahapTiga->file_jadwal);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_jadwal')->store('sintaks/tahap3', 'public');
            $tahapTiga->file_jadwal = $filePath;
        }
        
        $tahapTiga->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 3 berhasil disimpan');
    }

    public function tahap2($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain dengan pesan error
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
        
        // Cek apakah tahap 1 sudah divalidasi
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapSatu')
                                       ->firstOrFail();
        
        // Pastikan tahap 1 sudah valid sebelum lanjut ke tahap 2
        if (!$sintaks->sintaksTahapSatu || $sintaks->sintaksTahapSatu->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 1 terlebih dahulu.');
        }
        
        // Ambil data tahap dua jika sudah ada
        $tahapDua = $sintaks->sintaksTahapDua;
        
        // Jika belum ada, buat yang baru
        if (!$tahapDua) {
            $tahapDua = new \App\Models\SintaksTahapDua();
            $tahapDua->sintaks_id = $sintaks->id;
            $tahapDua->status_validasi = 'pending';
            $tahapDua->save();
            
            return redirect()->route('user.materi.tahap2', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 2.');
        }
        
        return view('frontend.user.sintaks.tahap2', compact('materi', 'kelompok', 'sintaks', 'tahapDua'));
    }
    
    public function simpanTahap2(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'deskripsi_rancangan' => 'required|string',
            'file_rancangan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->firstOrFail();
        
        // Cek apakah tahap 1 sudah divalidasi
        if (!$sintaks->sintaksTahapSatu || $sintaks->sintaksTahapSatu->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 1 terlebih dahulu.');
        }
        
        // Ambil atau buat tahap dua
        $tahapDua = $sintaks->sintaksTahapDua;
        if (!$tahapDua) {
            $tahapDua = new \App\Models\SintaksTahapDua();
            $tahapDua->sintaks_id = $sintaks->id;
        }
        
        // Update data tahap dua
        $tahapDua->deskripsi_rancangan = $request->deskripsi_rancangan;
        $tahapDua->status_validasi = 'pending';
        
        // Upload file jika ada
        if ($request->hasFile('file_rancangan')) {
            // Hapus file lama jika ada
            if ($tahapDua->file_rancangan && Storage::disk('public')->exists($tahapDua->file_rancangan)) {
                Storage::disk('public')->delete($tahapDua->file_rancangan);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_rancangan')->store('sintaks/tahap2', 'public');
            $tahapDua->file_rancangan = $filePath;
        }
        
        $tahapDua->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 2 berhasil disimpan');
    }
    
    public function tahap1($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
    
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
    
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
    
        // Jika kelompok tidak ditemukan, redirect ke halaman lain dengan pesan error
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil atau buat sintaks utama
        $sintaks = \App\Models\SintaksBaru::firstOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id
            ],
            [
                'status_validasi' => 'pending'
            ]
        );
    
        // Ambil data tahap satu jika sudah ada
        $tahapSatu = $sintaks->sintaksTahapSatu;
    
        // Jika tidak ada, buat yang baru
        if (!$tahapSatu) {
            $tahapSatu = new \App\Models\SintaksTahapSatu();
            $tahapSatu->sintaks_id = $sintaks->id;
            $tahapSatu->status_validasi = 'pending';
            $tahapSatu->save();
            
            return redirect()->route('user.materi.tahap1', $materi->slug)
                             ->with('success', 'Silakan mulai mengerjakan tahap 1.');
        }
    
        return view('frontend.user.sintaks.tahap1', compact('materi', 'kelompok', 'sintaks', 'tahapSatu'));
    }
    
    public function simpanTahap1(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'orientasi_masalah' => 'required|string',
            'rumusan_masalah' => 'required|string',
            'file_analisis' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->firstOrFail();
        
        // Ambil atau buat tahap satu
        $tahapSatu = $sintaks->sintaksTahapSatu;
        if (!$tahapSatu) {
            $tahapSatu = new \App\Models\SintaksTahapSatu();
            $tahapSatu->sintaks_id = $sintaks->id;
        }
        
        // Update data tahap satu
        $tahapSatu->orientasi_masalah = $request->orientasi_masalah;
        $tahapSatu->rumusan_masalah = $request->rumusan_masalah;
        $tahapSatu->status_validasi = 'pending';
        
        // Upload file jika ada
        if ($request->hasFile('file_analisis')) {
            // Hapus file lama jika ada
            if ($tahapSatu->file_analisis && Storage::disk('public')->exists($tahapSatu->file_analisis)) {
                Storage::disk('public')->delete($tahapSatu->file_analisis);
            }
            
            // Simpan file baru
            $filePath = $request->file('file_analisis')->store('sintaks/tahap1', 'public');
            $tahapSatu->file_analisis = $filePath;
        }
        
        $tahapSatu->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 1 berhasil disimpan');
    }

    public function buka($slug)
    {
        $user = Auth::user();

        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $materi = Materi::where('slug', $slug)->withCount(['babs', 'latihans'])->firstOrFail();

        $babs = Bab::where('materi_id', $materi->id)->get();

        $latihans = Latihan::where('materi_id', $materi->id)->get();

        $judulMateri = $materi->judul;

        $babAttempts = BabAttempt::where('user_id', $user->id)
            ->whereIn('bab_id', $babs->pluck('id'))
            ->get();

        // Check apakah semua status dalam $babs adalah 1
        $semuaBabsSelesai = $babs->every(function ($bab) {
            return $bab->status == 1;
        });

        // Untuk Latihan sesuai status babAttempts
        $semuaBabAttemptsSelesai = $babs->every(function ($bab) use ($babAttempts) {
            // Temukan BabAttempt yang sesuai dengan Bab tersebut
            $babAttempt = $babAttempts->where('bab_id', $bab->id)->first();

            // Periksa apakah BabAttempt ditemukan dan memiliki status 1
            return $babAttempt && $babAttempt->status == 1;
        });

        // Jika semua status adalah 1, tampilkan latihan
        $bolehLatihan = $semuaBabAttemptsSelesai ? Latihan::where('materi_id', $materi->id)->get() : collect();

        return view('frontend.user.materi.buka', [
            'materi' => $materi,
            'babs' => $babs,
            'judulMateri' => $judulMateri,
            'latihans' => $latihans,
            'bolehLatihan' => $bolehLatihan,
            'babAttempts' => $babAttempts,
            'semuaBabsSelesai' => $semuaBabsSelesai,
            'semuaBabAttemptsSelesai' => $semuaBabAttemptsSelesai
        ]);
    }
    
    public function pelajari($slug)
    {
        $user = Auth::user();

        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bab = Bab::where('slug', $slug)->with('materi')->firstOrFail();

        if ($bab) {
            $materiId = $bab->materi->id;

            $renderedMarkdown = Markdown::convertToHtml($bab->isi);

            //* Lanjut Materi Selanjutnya
            $nextBab = Bab::where('materi_id', $materiId)->where('id', '>', $bab->id)->with('materi')->orderBy('id', 'asc')->first();

            $materis = Materi::findOrFail($materiId)->withCount('babs')->orderBy('id', 'asc')->get();

            $babAttempts = BabAttempt::where('user_id', $user->id)
                ->where('bab_id', $bab->id)
                ->get();

            // dd($babAttempts);

            return view('frontend.user.materi.bab.pelajari', ['bab' => $bab, 'materiId' => $materiId, 'renderedMarkdown' => $renderedMarkdown, 'nextBab' => $nextBab, 'materis' => $materis, 'babAttempts' => $babAttempts]);
        } else {
            // Handle jika bab tidak ditemukan
            return abort(404);
        }
    }

    public function selesaikanMateri($slug)
    {
        $bab = Bab::where('slug', $slug)->firstOrFail();

        if ($bab) {
            BabAttempt::create([
                'bab_id' => $bab->id,
                'user_id' => Auth::user()->id,
                'status' => 1,
            ]);

            return redirect('/user/materi/' . $slug . '/pelajari')->with('message', 'Bab Materi berhasil diselesaikan.');
        } else {
            return redirect('/user/materi/' . $slug . '/pelajari')->with('message', 'Tidak ada Bab Materi yang ditemukan.');
        }
    }

    public function bukaLatihan($materiSlug)
    {
        $user = Auth::user();

        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $materi = Materi::where('slug', $materiSlug)->withCount(['latihans'])->firstOrFail();
        $latihans = Latihan::where('materi_id', $materi->id)->paginate(5);
        $judulMateri = $materi->judul;

        // dd($materi);

        $latihanAttempts = LatihanAttempt::where('user_id', $user->id)
            ->whereIn('latihan_id', $latihans->pluck('id'))
            ->get();

        // Check apakah semua status dalam $babs adalah 1
        $semuaLatihansSelesai = $latihans->every(function ($latihan) {
            return $latihan->status == 2;
        });

        // Untuk Latihan sesuai status babAttempts
        $semuaLatihanAttemptsSelesai = $latihans->every(function ($latihan) use ($latihanAttempts) {
            // Temukan BabAttempt yang sesuai dengan Bab tersebut
            $latihanAttempt = $latihanAttempts->where('latihan_id', $latihan->id)->first();

            // Periksa apakah BabAttempt ditemukan dan memiliki status 1
            return $latihanAttempt && $latihanAttempt->status == 2;
        });

        // dd($semuaBabAttemptsSelesai);

        // Jika semua status adalah 1, tampilkan latihan
        $bolehLatihan = $semuaLatihanAttemptsSelesai ? Latihan::where('materi_id', $materi->id)->get() : collect();

        // Mengirimkan parameter status = 2 ke tampilan blade
        return view('frontend.user.latihan.index', [
            'latihans' => $latihans,
            'materi' => $materi,
            'judulMateri' => $judulMateri,
            'bolehLatihan' => $bolehLatihan,
            'latihanAttempts' => $latihanAttempts,
            'semuaLatihansSelesai' => $semuaLatihansSelesai,
            'semuaLatihanAttemptsSelesai' => $semuaLatihanAttemptsSelesai,
            'status' => 2, // Menambahkan parameter status dengan nilai 2
        ]);
    }


    public function kerjakan($slug)
    {
        $user = Auth::user();

        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Mengambil data latihan dan test terkait
        $latihan = Latihan::where('slug', $slug)->with('getTest')->firstOrFail();

        $latihanAttemptsAnswer = LatihanAttempt::where('user_id', $user->id)
            ->where('latihan_id', $latihan->id)
            ->get();

        // Mengambil semua id dari latihanAttempt
        $latihanAttemptIds = $latihanAttemptsAnswer->pluck('id');

        // Mengambil LatihanAnswer yang memiliki latihan_attempt_id sesuai dengan latihanAttemptIds
        $latihanAnswers = LatihanAnswer::whereIn('latihan_attempt_id', $latihanAttemptIds)
            ->get();

        // Menambahkan latihan_attempt_id, status, dan updated_at ke dalam setiap elemen array
        $typedAnswers = $latihanAnswers->map(function ($answer) {
            $attempt = LatihanAttempt::find($answer->latihan_attempt_id);
            return [
                'latihan_attempt_id' => $answer->latihan_attempt_id,
                'status' => $attempt->status,
                'updated_at' => $attempt->updated_at,
                'typed_answer' => $answer->typed_answer,
            ];
        });

        // Mengonversi hasil ke dalam bentuk string jika diperlukan
        $typedAnswersString = $latihanAnswers->implode(', ');

        // dd($typedAnswers);

        $latihanAttemptsAnswer2 = LatihanAttempt::where('user_id', $user->id)->where('latihan_id', $latihan->id)->where('status', 2)->get();

        $latihanAttemptIdsBenar = $latihanAttemptsAnswer2->pluck('id');

        $latihanAnswer2 = LatihanAnswer::whereIn('latihan_attempt_id', $latihanAttemptIdsBenar)->get('typed_answer');

        if ($latihan) {
            if ($latihan->getTest->count() > 0) {

                $qna = Test::where('latihan_id', $latihan->id)->with('question', 'jawaban')->get();

                // Lanjutkan dengan mengatur variabel dan melanjutkan proses
                $materiId = $latihan->materi->id;

                $nextLatihan = Latihan::where('materi_id', $materiId)
                    ->where('id', '>', $latihan->id)
                    ->with('materi')
                    ->orderBy('id', 'asc')
                    ->first();

                $materis = Materi::findOrFail($materiId)
                    ->withCount('latihans')
                    ->orderBy('id', 'asc')
                    ->get();

                $latihanAttempts = LatihanAttempt::where('user_id', $user->id)
                    ->where('latihan_id', $latihan->id)
                    ->get();

                return view('frontend.user.latihan.kerjakan', [
                    'success' => true,
                    'quiz' => $latihan,
                    'qna' => $qna,
                    'latihans' => $latihan,
                    'materiId' => $materiId,
                    'nextLatihan' => $nextLatihan,
                    'materis' => $materis,
                    'latihanAttempts' => $latihanAttempts,
                    'typedAnswers' => $typedAnswers,
                    'latihanAnswer2' => $latihanAnswer2,
                    'latihanAttemptsAnswer' => $latihanAttemptsAnswer
                ]);
            } else {
                return view('frontend.user.latihan.kerjakan', [
                    'success' => false,
                    'msg' => 'Latihan ini belum tersedia',
                    'quiz' => $latihan
                ]);
            }
        } else {
            return abort(404);
        }
    }


    public function jawab(Request $request)
    {
        $latihan_attempt_id = LatihanAttempt::insertGetId([
            'latihan_id' => $request->latihan_id,
            'user_id' => Auth::user()->id
        ]);

        $qcount = count($request->q);

        $isAllCorrect = true;

        if ($qcount > 0) {
            for ($i = 0; $i < $qcount; $i++) {
                $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));

                $now = Carbon::now();

                LatihanAnswer::insert([
                    'latihan_attempt_id' => $latihan_attempt_id,
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'created_at' => $now,
                ]);

                //Todo: Check typed_answer dan answer apakah sama
                $isCorrect = $this->checkAnswer($request->q[$i], $typedAnswer);

                if (!$isCorrect) {
                    $isAllCorrect = false;
                }
            }
        }

        $status = $isAllCorrect ? 2 : 1;
        LatihanAttempt::where('id', $latihan_attempt_id)->update([
            'status' => $status
        ]);

        $message = ($status == 2) ? 'Selamat Anda Berhasil' : 'Maaf coba lagi';

        return Redirect::back()->with('message', $message);
    }

    private function checkAnswer($questionId, $typedAnswer)
    {
        $correctAnswer = Answer::where('soal_id', $questionId)->first()->answer;

        return $typedAnswer === $correctAnswer;
    }

    // * PRE TEST
    public function bukaPreTest()
    {
        $user = Auth::user();

        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $qnaPreTest = PrePost::where('judulPrePost', 'Pre Test')->with('getPrePostTest')->get();

        $preTestAttemptsAnswer = PreTestAttempt::where('user_id', $user->id)->where('pre_post_id', 4)->get();

        // Mengambil semua id dari preTestAttempt
        $preTestAttemptIds = $preTestAttemptsAnswer->pluck('id');

        // Mengambil PreTestAnswer yang memiliki pre_test_attempt_id sesuai dengan preTestAttemptsIds
        $preTestAnswers = PreTestAnswer::whereIn('pre_test_attempt_id', $preTestAttemptIds)->get();

        // dd($preTestAnswers);

        // Menambahkan pre_test_attempt_id, status, dan updated_at ke dalam setiap elemen array
        $typedAnswers = $preTestAnswers->map(function ($answer) {
            $attempt = PreTestAttempt::find($answer->pre_test_attempt_id);
            return [
                'pre_test_attempt_id' => $answer->pre_test_attempt_id,
                'status' => $attempt->status,
                'created_at' => $attempt->created_at,
                'updated_at' => $attempt->updated_at,
                'typed_answer' => $answer->typed_answer,
                'nilai' => $answer->nilai,
                'total_nilai' => $attempt->total_nilai, // Tambahkan total_nilai
            ];
        });

        // Mengonversi hasil ke dalam bentuk string jika diperlukan
        $typedAnswersString = $preTestAnswers->implode(', ');

        $preTestAttemptsAnswer2 = PreTestAttempt::where('user_id', $user->id)->where('pre_post_id', 3)->where('status', 2)->get();

        // dd($preTestAttemptsAnswer2);

        $preTestAttemptIdsBenar = $preTestAttemptsAnswer2->pluck('id');

        $preTestAnswers2 = PreTestAnswer::whereIn('pre_test_attempt_id', $preTestAttemptIdsBenar)->get('typed_answer');

        if (count($qnaPreTest) > 0) {
            if (count($qnaPreTest[0]['getPrePostTest']) > 0) {

                $qna = PrePostTest::where('pre_post_id', $qnaPreTest[0]['id'])->with('question', 'jawaban')->get();

                $pretests = PrePost::where('judulPrePost', 'Pre Test')->with('materi')->first();

                // dd($pretests);

                $materiId = $pretests->materi->id;

                // dd($materiId);

                $nextLatihan = PrePost::where('materi_id', $materiId)->where('id', '>', $pretests)->with('materi')->orderBy('id', 'asc')->first();

                $materis = Materi::findOrFail($materiId)->withCount('preposts')->orderBy('id', 'asc');

                PreTestAttempt::updateOrCreate(
                    ['user_id' => $user->id, 'pre_post_id' => $pretests->id],
                    ['created_at' => Carbon::now()]
                );

                $preTestAttempts = PreTestAttempt::where('user_id', $user->id)
                ->where('pre_post_id', $pretests->id)
                ->get(['id', 'status', 'total_nilai', 'created_at', 'updated_at']); // Ambil total_nilai
            
                // dd($preTestAttempts);

                return view('frontend.user.pretest.index', [
                    'success' => true,
                    'quiz' => $qnaPreTest,
                    'qna' => $qna,
                    'pretests' => $pretests,
                    'materiId' => $materiId,
                    'nextLatihan' => $nextLatihan,
                    'materis' => $materis,
                    'preTestAttempts' => $preTestAttempts,
                    'typedAnswers' => $typedAnswers,
                    'preTestAnswer2' => $preTestAnswers2,
                    'preTestAttemptsAnswer' => $preTestAttemptsAnswer
                ]);
            } else {
                return view('frontend.user.pretest.index', [
                    'success' => false,
                    'msg' => 'Pre Test ini belum tersedia',
                    'quiz' => $qnaPreTest
                ]);
            }
        } else {
            return abort(404);
        }
    }

    public function jawabPreTest(Request $request)
    {
        $user_id = Auth::user()->id;
        $pre_post_id = $request->pre_post_id;
        
        // Cari atau buat PreTestAttempt
        $preTestAttempt = PreTestAttempt::updateOrCreate(
            ['user_id' => $user_id, 'pre_post_id' => $pre_post_id],
            ['created_at' => Carbon::now()]
        );
        
        $pre_test_attempt_id = $preTestAttempt->id;
        $qcount = count($request->q);
        $correctAnswersCount = 0; // Hitung jumlah soal yang benar
        $totalNilai = 0; // Inisialisasi total nilai
        
        // Inisialisasi array untuk menyimpan hasil setiap soal
        $detailScores = [];
        
        // Nilai per soal dihitung secara dinamis
        $nilaiPerSoal = 100 / $qcount; // Nilai per soal berdasarkan jumlah soal
        
        if ($qcount > 0) {
            for ($i = 0; $i < $qcount; $i++) {
                $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));
        
                // Simpan jawaban
                PreTestAnswer::create([
                    'pre_test_attempt_id' => $pre_test_attempt_id,
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'created_at' => Carbon::now(),
                ]);
        
                // Periksa jawaban dan tentukan nilai
                $isCorrect = $this->checkPreTestAnswer($request->q[$i], $typedAnswer);
                $nilai = $isCorrect ? $nilaiPerSoal : 0; // Nilai per soal sesuai distribusi dinamis
        
                if ($isCorrect) {
                    $correctAnswersCount++; // Tambah jumlah soal yang benar
                }
        
                // Update nilai jawaban
                PreTestAnswer::where('pre_test_attempt_id', $pre_test_attempt_id)
                    ->where('soal_id', $request->q[$i])
                    ->update(['nilai' => $nilai]);
                
                // Dapatkan jawaban yang benar dari database untuk feedback
                $correctAnswer = Answer::where('soal_id', $request->q[$i])->first()->answer;
    
                // Tambahkan detail nilai soal
                $detailScores[] = [
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'nilai' => $nilai,
                    'correct_answer' => $correctAnswer,
                    'is_correct' => $isCorrect ? 'Benar' : 'Salah',
                ];
            }
        }
    
        // Hitung total nilai dalam format desimal
        $totalNilai = $nilaiPerSoal * $correctAnswersCount;
        
        // Update total_nilai di PreTestAttempt
        $preTestAttempt->update(['total_nilai' => number_format($totalNilai, 2, '.', '')]);
        
        // Tentukan status berdasarkan jumlah jawaban yang benar
        $status = ($correctAnswersCount === $qcount) ? 2 : 1;
        $preTestAttempt->update(['status' => $status]);
        
        // Pesan sukses
        $message = ($status == 2) 
            ? 'Selamat, Anda berhasil menyelesaikan Pre Test dengan sempurna!' 
            : 'Selamat, Anda berhasil menyelesaikan Pre Test!';
        
        // Menampilkan detail skor untuk analisis lebih lanjut
        return Redirect::back()->with('message', $message);
    }


    private function checkPreTestAnswer($questionId, $typedAnswer)
    {
        $correctAnswer = Answer::where('soal_id', $questionId)->first()->answer;

        return $typedAnswer === $correctAnswer;
    }

    // * POST TEST
    public function bukaPostTest()
    {
        $user = Auth::user();
        
        // Memeriksa apakah user sudah login
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Ambil data PostTest berdasarkan judul "Post Test"
        $posttests = PrePost::where('judulPrePost', 'Post Test')->with('materi')->first(); 
    
        // Periksa apakah $posttests ditemukan dan materi ada
        if (!$posttests || !$posttests->materi) {
            return view('frontend.user.posttest.index', [
                'success' => false,
                'msg' => 'Post Test atau Materi tidak ditemukan.',
                'quiz' => collect(), // Kirim koleksi kosong untuk menghindari error
            ]);
        }
    
        // Mengambil data PostTestAttempts yang sudah ada untuk user
        $postTestAttemptsAnswer = PostTestAttempt::where('user_id', $user->id)->where('pre_post_id', 4)->get();
        $postTestAttemptIds = $postTestAttemptsAnswer->pluck('id');
        $postTestAnswers = PostTestAnswer::whereIn('post_test_attempt_id', $postTestAttemptIds)->get();
    
        // Format data jawaban
        $typedAnswers = $postTestAnswers->map(function ($answer) {
            $attempt = PostTestAttempt::find($answer->post_test_attempt_id);
            return [
                'post_test_attempt_id' => $answer->post_test_attempt_id,
                'status' => $attempt->status,
                'created_at' => $attempt->created_at,
                'updated_at' => $attempt->updated_at,
                'typed_answer' => $answer->typed_answer,
                'nilai' => $answer->nilai,
                'total_nilai' => $attempt->total_nilai, // Tambahkan total_nilai
            ];
        });
    
        // Mengambil data PostTestAttempt yang sudah benar
        $postTestAttemptsAnswer2 = PostTestAttempt::where('user_id', $user->id)->where('pre_post_id', 4)->where('status', 2)->get();
        $postTestAttemptIdsBenar = $postTestAttemptsAnswer2->pluck('id');
        $postTestAnswers2 = PostTestAnswer::whereIn('post_test_attempt_id', $postTestAttemptIdsBenar)->get('typed_answer');
    
        // Jika tidak ada pertanyaan di PostTest
        $qna = PrePostTest::where('pre_post_id', $posttests->id)->with('question', 'jawaban')->get();
        if ($qna->isEmpty()) {
            return view('frontend.user.posttest.index', [
                'success' => false,
                'msg' => 'Post Test ini belum tersedia. Silakan hubungi administrator.',
                'quiz' => $qna,
            ]);
        }
    
        // Mengambil data materi dan latihannya yang akan datang
        $materiId = $posttests->materi->id;
        $nextLatihan = PrePost::where('materi_id', $materiId)->where('id', '>', $posttests->id)->with('materi')->orderBy('id', 'asc')->first();
        $materis = Materi::findOrFail($materiId)->withCount('preposts')->orderBy('id', 'asc');
    
        // Update atau buat PostTestAttempt
        PostTestAttempt::updateOrCreate(
            ['user_id' => $user->id, 'pre_post_id' => $posttests->id],
            ['created_at' => Carbon::now()]
        );
    
        // Ambil data PostTestAttempt
        $postTestAttempts = PostTestAttempt::where('user_id', $user->id)
            ->where('pre_post_id', $posttests->id)
            ->get(['id', 'status', 'total_nilai', 'created_at', 'updated_at']);
    
        // Kirim data ke view
        return view('frontend.user.posttest.index', [
            'success' => true,
            'quiz' => $qna,
            'qna' => $qna,
            'posttests' => $posttests,
            'materiId' => $materiId,
            'nextLatihan' => $nextLatihan,
            'materis' => $materis,
            'postTestAttempts' => $postTestAttempts,
            'typedAnswers' => $typedAnswers,
            'postTestAnswer2' => $postTestAnswers2,
            'postTestAttemptsAnswer' => $postTestAttemptsAnswer,
        ]);
    }

    public function jawabPostTest(Request $request)
    {
        $user_id = Auth::user()->id;
        $pre_post_id = $request->pre_post_id;
        
        // Cari atau buat PostTestAttempt
        $postTestAttempt = PostTestAttempt::updateOrCreate(
            ['user_id' => $user_id, 'pre_post_id' => $pre_post_id],
        );
    
        $post_test_attempt_id = $postTestAttempt->id;
        $qcount = count($request->q);
        $totalNilai = 0.00; // Nilai default decimal
        
        // Hitung nilai berdasarkan jumlah soal
        if ($qcount > 0) {
            for ($i = 0; $i < $qcount; $i++) {
                $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));
    
                // Simpan jawaban
                PostTestAnswer::create([
                    'post_test_attempt_id' => $post_test_attempt_id,
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'created_at' => Carbon::now(),
                ]);
    
                // Periksa jawaban dan hitung nilai
                $isCorrect = $this->checkPostTestAnswer($request->q[$i], $typedAnswer);
                $nilai = $isCorrect ? (100.00 / $qcount) : 0.00; // Nilai per soal berdasarkan total soal
                $totalNilai += $nilai; // Akumulasi nilai total
    
                // Update nilai jawaban pada tabel PostTestAnswer
                PostTestAnswer::where('post_test_attempt_id', $post_test_attempt_id)
                    ->where('soal_id', $request->q[$i])
                    ->update(['nilai' => $nilai]);
            }
        }
    
        // Update total_nilai di PostTestAttempt dengan nilai decimal
        $postTestAttempt->update(['total_nilai' => number_format($totalNilai, 2)]); // Format ke decimal dengan dua angka di belakang koma
    
        // Tentukan status berdasarkan hasil jawaban
        $status = ($totalNilai == 100.00) ? 2 : 1; // Status sukses atau tidak
        $postTestAttempt->update(['status' => $status]);
    
        // Pesan sukses
        $message = ($status == 2) 
            ? 'Selamat, Anda berhasil menyelesaikan Post Test dengan sempurna!' 
            : 'Selamat, Anda berhasil menyelesaikan Post Test!';
    
        return Redirect::back()->with('message', $message);
    }

    private function checkPostTestAnswer($questionId, $typedAnswer)
    {
        $correctAnswer = Answer::where('soal_id', $questionId)->first()->answer;

        return $typedAnswer === $correctAnswer;
    }

    public function tahap8($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Cek apakah PJBL Sintaks diaktifkan untuk materi ini
        if (!$materi->pjbl_sintaks_active) {
            return redirect()->route('user.materi.index')
                ->with('error', 'PJBL Sintaks tidak aktif untuk materi ini');
        }
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil sintaks
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with(['sintaksTahapTuju'])
                                       ->firstOrFail();
        
        // Pastikan tahap 7 sudah valid sebelum lanjut ke tahap 8
        if (!$sintaks->sintaksTahapTuju || $sintaks->sintaksTahapTuju->status_validasi !== 'valid') {
            return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                            ->with('error', 'Anda harus menyelesaikan dan mendapatkan validasi untuk Tahap 7 terlebih dahulu.');
        }
        
        // Ambil data tahap delapan jika sudah ada
        $tahapDelapan = $sintaks->sintaksTahapDelapan;
        
        // Jika belum ada, buat yang baru
        if (!$tahapDelapan) {
            $tahapDelapan = new \App\Models\SintaksTahapDelapan();
            $tahapDelapan->sintaks_id = $sintaks->id;
            $tahapDelapan->status_validasi = 'pending';
            $tahapDelapan->save();
            
            // Buat entri refleksi individu untuk setiap anggota kelompok
            $anggotaKelompok = $kelompok->anggotas;
            foreach ($anggotaKelompok as $anggota) {
                $refleksi = new \App\Models\SintaksTahapDelapanRefleksi();
                $refleksi->sintaks_evaluasi_id = $tahapDelapan->id;
                $refleksi->user_id = $anggota->user_id;
                $refleksi->save();
            }
            
            return redirect()->route('user.materi.tahap8', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 8.');
        }
        
        // Load refleksi individu dengan relasi user
        $tahapDelapan->load('refleksiIndividu.user');
        
        // Refleksi untuk user yang sedang login
        $refleksiSaya = $tahapDelapan->refleksiIndividu->where('user_id', auth()->id())->first();

        return view('frontend.user.sintaks.tahap8', compact('materi', 'kelompok', 'sintaks', 'tahapDelapan', 'refleksiSaya'));
    }

    public function simpanTahap8(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'refleksi_kelompok' => 'required|string',
            'pembelajaran' => 'required|string',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks utama
        $sintaks = \App\Models\SintaksBaru::where('materi_id', $materi->id)
                                       ->where('kelompok_id', $kelompok->id)
                                       ->with('sintaksTahapDelapan')
                                       ->firstOrFail();
        
        // Pastikan tahap delapan sudah ada
        if (!$sintaks->sintaksTahapDelapan) {
            return redirect()->route('user.materi.tahap8', ['slug' => $slug])
                            ->with('error', 'Tahap 8 belum dibuat.');
        }
        
        // Update data tahap delapan
        $tahapDelapan = $sintaks->sintaksTahapDelapan;
        $tahapDelapan->refleksi_kelompok = $request->refleksi_kelompok;
        $tahapDelapan->pembelajaran = $request->pembelajaran;
        $tahapDelapan->status_validasi = 'pending';
        $tahapDelapan->save();
    
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])
                        ->with('success', 'Tahap 8 berhasil disimpan');
    }
    
    public function simpanRefleksiIndividu(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'kendala' => 'required|string',
            'pembelajaran' => 'required|string',
            'refleksi_id' => 'required|exists:sintaks_tahap_delapan_refleksis,id',
        ]);
        
        // Ambil refleksi
        $refleksi = \App\Models\SintaksTahapDelapanRefleksi::findOrFail($request->refleksi_id);
        
        // Pastikan refleksi milik user yang sedang login
        if ($refleksi->user_id != auth()->id()) {
            return redirect()->route('user.materi.tahap8', ['slug' => $slug])
                            ->with('error', 'Anda tidak berhak mengubah refleksi ini.');
        }
        
        // Update data refleksi
        $refleksi->kendala = $request->kendala;
        $refleksi->pembelajaran = $request->pembelajaran;
        $refleksi->save();
        
        return redirect()->route('user.materi.tahap8', ['slug' => $slug])
                        ->with('success', 'Refleksi individu berhasil disimpan');
    }
}
