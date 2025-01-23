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
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        // Jika kelompok tidak ditemukan, redirect ke halaman lain
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
        
        // Ambil sintaks berdasarkan materi_id dan kelompok_id
        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->get();
        
        // Pastikan setiap sintaks memiliki status validasi yang sesuai
        foreach ($sintaks as $sintak) {
            $sintak->status_validasi = $sintak->status_validasi ?? 'invalid'; // Default ke 'invalid' jika kosong
        }
        
        return view('frontend.user.sintaks.index', compact('materi', 'sintaks', 'kelompok'));
    }

    public function tahap7($slug)
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
    
        // Ambil sintaks tahap 7
        $sintaks = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_7')
            ->first();
    
        // Jika tidak ada sintaks untuk kelompok ini (pertama kali mengerjakan), buat sintaks baru
        if (!$sintaks) {
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_7';
            $sintaks->status_validasi = 'pending'; // Status awal: pending
            $sintaks->save();  // Simpan sintaks baru
            
            return redirect()->route('user.materi.tahap7', $materi->slug)
                            ->with('success', 'Silakan meminta penilaian dari guru.');
        }
    
        // Kirim data ke view
        return view('frontend.user.sintaks.tahap7', compact('materi', 'kelompok', 'sintaks'));
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
    
        // Ambil sintaks tahap 7
        $sintaks = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_7')
            ->firstOrFail();
    
        // Update status validasi menjadi 'pending'
        $sintaks->status_validasi = 'pending';
        $sintaks->save();
    
        return redirect()->route('user.materi.tahap7', $materi->slug)
                        ->with('success', 'Permintaan penilaian telah dikirim.');
    }


    public function tahap6($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil atau buat sintaks tahap 6
        $sintaks = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_6')
            ->first();

        if (!$sintaks) {
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_6';
            $sintaks->save();  // Simpan sintaks baru
            
            return redirect()->route('user.materi.tahap6', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 6.');
        }

        return view('frontend.user.sintaks.tahap6', compact('materi', 'kelompok', 'sintaks'));
    }

    public function simpanTahap6(Request $request, $slug)
    {
        // Validasi upload file (opsional)
        $request->validate([
            'file_proyek' => 'nullable|file|mimes:pdf,docx,xlsx|max:10240', // File proyek (opsional)
            'file_laporan' => 'nullable|file|mimes:pdf,docx,xlsx|max:10240', // File laporan (opsional)
        ]);
        
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks tahap 6
        $sintaks = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_6')
            ->firstOrFail();
    
        // Simpan file proyek jika diupload
        if ($request->hasFile('file_proyek')) {
            // Hapus file proyek lama jika ada
            if ($sintaks->file_proyek && Storage::disk('public')->exists($sintaks->file_proyek)) {
                Storage::disk('public')->delete($sintaks->file_proyek);
            }
    
            // Simpan file proyek baru
            $fileProyekPath = $request->file('file_proyek')->store('proyek', 'public');
            $sintaks->file_proyek = $fileProyekPath;
        }
    
        // Simpan file laporan jika diupload
        if ($request->hasFile('file_laporan')) {
            // Hapus file laporan lama jika ada
            if ($sintaks->file_laporan && Storage::disk('public')->exists($sintaks->file_laporan)) {
                Storage::disk('public')->delete($sintaks->file_laporan);
            }
    
            // Simpan file laporan baru
            $fileLaporanPath = $request->file('file_laporan')->store('laporan', 'public');
            $sintaks->file_laporan = $fileLaporanPath;
        }
    
        // Simpan perubahan ke database
        $sintaks->save();
        
        return redirect()->route('user.materi.tahap6', ['slug' => $slug])->with('success', 'File berhasil diupload.');
    }

    public function simpanTahap5(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Ambil sintaks tahap 5
        $sintaks = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_5')
            ->firstOrFail();
    
        // Update status To-Do List
        $toDoList = json_decode($sintaks->to_do_list, true); // Decode JSON ke array
        foreach ($request->to_do_list as $key => $task) {
            $toDoList[$key]['status'] = $task['status'];
        }
    
        // Konversi array ke JSON sebelum disimpan
        $sintaks->to_do_list = json_encode($toDoList);
        $sintaks->save();
    
        return redirect()->route('user.materi.tahap5', $materi->slug)
                        ->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function tahap5($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Ambil sintaks tahap 3 untuk mengambil tugas_anggota
        $sintaksTahap3 = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_3')
            ->first();

        if (!$sintaksTahap3) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Tahap 3 belum selesai.');
        }

        // Ambil atau buat sintaks tahap 5
        $sintaksTahap5 = Sintaks::where('materi_id', $materi->id)
            ->where('kelompok_id', $kelompok->id)
            ->where('status_tahap', 'tahap_5')
            ->first();

        if (!$sintaksTahap5) {
            $sintaksTahap5 = new Sintaks();
            $sintaksTahap5->materi_id = $materi->id;
            $sintaksTahap5->kelompok_id = $kelompok->id;
            $sintaksTahap5->status_tahap = 'tahap_5';

            // Inisialisasi To-Do List dari tugas_anggota di Tahap 3
            $toDoList = [];
            $tugasAnggota = json_decode($sintaksTahap3->tugas_anggota, true);
            foreach ($tugasAnggota as $key => $tugas) {
                $toDoList[] = [
                    'tugas' => $tugas,
                    'anggota' => $kelompok->anggotas[$key]->user->name,
                    'status' => 'pending', // Status default
                ];
            }

            // Konversi array ke JSON sebelum disimpan
            $sintaksTahap5->to_do_list = json_encode($toDoList);
            $sintaksTahap5->save();  // Simpan sintaks baru
            
            return redirect()->route('user.materi.tahap5', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 5.');
        }

        // Pastikan to_do_list selalu berupa array
        if (is_null($sintaksTahap5->to_do_list)) {
            $sintaksTahap5->to_do_list = json_encode([]);
            $sintaksTahap5->save();
        }

        return view('frontend.user.sintaks.tahap5', compact('materi', 'kelompok', 'sintaksTahap5'));
    }

    public function tahap4($slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        $sintaks = Sintaks::where('materi_id', $materi->id)
        ->where('kelompok_id', $kelompok->id)
        ->where('status_tahap', 'tahap_4')
        ->first();

        // Ambil sintaks tahap 4
        if (!$sintaks) {
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_4';
            $sintaks->save();  // Simpan sintaks baru
            
            // Redirect ke halaman tahap 4 dengan data sintaks yang baru
            return redirect()->route('user.materi.tahap4', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 4.');
        }

        return view('frontend.user.sintaks.tahap4', compact('materi', 'kelompok', 'sintaks'));
    }

    public function simpanTahap4(Request $request, $slug)
    {
        // Validasi upload file
        $request->validate([
            'file_jadwal' => 'required|file|mimes:pdf,docx,xlsx|max:10240', // Tentukan jenis file yang diizinkan
        ]);
    
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Simpan file di folder yang aman (storage/app/jadwal_proyek)
        $filePath = $request->file('file_jadwal')->store('jadwal_proyek', 'public');
    
        // Menyimpan data tahap 4
        $sintaks = Sintaks::updateOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
                'status_tahap' => 'tahap_4',
            ],
            [
                'file_jadwal' => $filePath, // Simpan path file di database
                'status_validasi' => 'invalid', // Status validasi awal
            ]
        );
        
        return redirect()->route('user.materi.tahap4', ['slug' => $slug])->with('success', 'Jadwal berhasil diupload.');
    }

    public function tahap3($slug)
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

        // Ambil anggota kelompok
        $anggotaKelompok = $kelompok->anggotas;

        // Ambil sintaks berdasarkan materi_id, kelompok_id, dan status_tahap
        $sintaks = Sintaks::where('materi_id', $materi->id)
                        ->where('kelompok_id', $kelompok->id)
                        ->where('status_tahap', 'tahap_3')
                        ->first();

        // Jika tidak ada sintaks untuk kelompok ini (pertama kali mengerjakan), buat sintaks baru
        if (!$sintaks) {
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_3';
            $sintaks->save();  // Simpan sintaks baru
            
            // Redirect ke halaman tahap 3 dengan data sintaks yang baru
            return redirect()->route('user.materi.tahap3', $materi->slug)
                            ->with('success', 'Silakan mulai mengerjakan tahap 3.');
        }

        // Kirim data anggota dan sintaks ke view
        return view('frontend.user.sintaks.tahap3', compact('materi', 'kelompok', 'sintaks', 'anggotaKelompok'));
    }

    public function simpanTahap3(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'deskripsi_proyek' => 'required|string',
            'tugas_anggota' => 'required|array',
            'tugas_anggota.*' => 'required|string',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }

        // Menyimpan data tahap 3 atau update jika sudah ada
        $sintak = Sintaks::updateOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
                'status_tahap' => 'tahap_3',
            ],
            [
                'deskripsi_proyek' => $request->deskripsi_proyek,
                'tugas_anggota' => json_encode($request->tugas_anggota), // Encode tugas anggota sebagai JSON
                'status_validasi' => 'invalid', // Status validasi awal
            ]
        );
        
        // Redirect kembali ke halaman daftar sintaks
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])->with('success', 'Tahap 3 berhasil disimpan');
    }

    public function tahap2($slug)
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
        
        // Ambil sintaks berdasarkan materi_id, kelompok_id, dan status_tahap
        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->where('status_tahap', 'tahap_2')
                          ->first();
        
        // Jika tidak ada sintaks untuk kelompok ini (pertama kali mengerjakan), buat sintaks baru
        if (!$sintaks) {
            // Buat data sintaks baru untuk tahap 2
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_2';
            $sintaks->save();  // Simpan sintaks baru
            
            // Redirect ke halaman tahap 2 dengan data sintaks yang baru
            return redirect()->route('user.materi.tahap2', $materi->slug)
                             ->with('success', 'Silakan mulai mengerjakan tahap 2.');
        }
    
        // Jika sintaks ada, lanjutkan ke halaman tahap 2
        return view('frontend.user.sintaks.tahap2', compact('materi', 'kelompok', 'sintaks'));
    }
    
    public function simpanTahap2(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'indikator_masalah' => 'required|string',
            'hasil_analisis' => 'required|string',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Menyimpan data tahap 2 atau update jika sudah ada
        $sintak = Sintaks::updateOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
                'status_tahap' => 'tahap_2',
            ],
            [
                'indikator_masalah' => $request->indikator_masalah,
                'hasil_analisis' => $request->hasil_analisis,
                'status_validasi' => 'invalid', // Status validasi awal
            ]
        );
        
        // Redirect kembali ke halaman daftar sintaks
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])->with('success', 'Tahap 2 berhasil disimpan');
    }
    
    public function tahap1($slug)
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
    
        // Ambil sintaks berdasarkan materi_id, kelompok_id, dan status_tahap
        $sintaks = Sintaks::where('materi_id', $materi->id)
                          ->where('kelompok_id', $kelompok->id)
                          ->where('status_tahap', 'tahap_1')
                          ->first();
    
        // Jika tidak ada sintaks untuk kelompok ini (pertama kali mengerjakan), buat sintaks baru
        if (!$sintaks) {
            // Buat data sintaks baru untuk tahap 1
            $sintaks = new Sintaks();
            $sintaks->materi_id = $materi->id;
            $sintaks->kelompok_id = $kelompok->id;
            $sintaks->status_tahap = 'tahap_1';
            $sintaks->save();  // Simpan sintaks baru
    
            // Redirect ke halaman tahap 1 dengan data sintaks yang baru
            return redirect()->route('user.materi.tahap1', $materi->slug)
                             ->with('success', 'Silakan mulai mengerjakan tahap 1.');
        }
    
        // Jika sintaks ada, lanjutkan ke halaman tahap 1
        return view('frontend.user.sintaks.tahap1', compact('materi', 'kelompok', 'sintaks'));
    }
    
    
    public function simpanTahap1(Request $request, $slug)
    {
        // Ambil materi berdasarkan slug
        $materi = Materi::where('slug', $slug)->firstOrFail();
        
        // Validasi data
        $request->validate([
            'orientasi_masalah' => 'required|string',
            'rumusan_masalah' => 'required|string',
        ]);
        
        // Ambil kelompok berdasarkan user yang sedang login
        $kelompok = Kelompok::whereHas('anggotas', function($query) {
            $query->where('user_id', auth()->id());
        })->first();
        
        if (!$kelompok) {
            return redirect()->route('user.materi.sintaks')->with('error', 'Anda tidak tergabung dalam kelompok.');
        }
    
        // Menyimpan data tahap 1 atau update jika sudah ada
        $sintak = Sintaks::updateOrCreate(
            [
                'materi_id' => $materi->id,
                'kelompok_id' => $kelompok->id,
                'status_tahap' => 'tahap_1',
            ],
            [
                'orientasi_masalah' => $request->orientasi_masalah,
                'rumusan_masalah' => $request->indikator,
                'status_validasi' => 'invalid', // Status validasi awal
            ]
        );
    
        // Redirect kembali ke halaman daftar sintaks
        return redirect()->route('user.materi.sintaks', ['slug' => $slug])->with('success', 'Tahap 1 berhasil disimpan');
    }
    // public function buka($slug)
    // {
    //     $user = Auth::user();

    //     // Memeriksa apakah user sudah login
    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // $materis = Materi::findOrFail($materi)->withCount(['babs', 'latihans'])->orderBy('id', 'ASC')->paginate(10);
    //     $materi = Materi::where('slug', $slug)->firstOrFail()->withCount(['babs', 'latihans'])->orderBy('id', 'ASC')->paginate(10);

    //     $babs = Bab::where('materi_id', $materi)->get();

    //     $latihans = Latihan::where('materi_id', $materi)->get();

    //     $judulMateri = $materi->firstWhere('id', $materi)->judul;

    //     $babAttempts = BabAttempt::where('user_id', $user->id)
    //         ->whereIn('bab_id', $babs->pluck('id'))
    //         ->get();

    //     // Check apakah semua status dalam $babs adalah 1
    //     $semuaBabsSelesai = $babs->every(function ($bab) {
    //         return $bab->status == 1;
    //     });

    //     // Untuk Latihan sesuai status babAttempts
    //     $semuaBabAttemptsSelesai = $babs->every(function ($bab) use ($babAttempts) {
    //         // Temukan BabAttempt yang sesuai dengan Bab tersebut
    //         $babAttempt = $babAttempts->where('bab_id', $bab->id)->first();

    //         // Periksa apakah BabAttempt ditemukan dan memiliki status 1
    //         return $babAttempt && $babAttempt->status == 1;
    //     });

    //     // dd($semuaBabAttemptsSelesai);

    //     // Jika semua status adalah 1, tampilkan latihan
    //     $bolehLatihan = $semuaBabAttemptsSelesai ? Latihan::where('materi_id', $materi)->get() : collect();

    //     // dd($bolehLatihan);

    //     return view('frontend.user.materi.buka', [
    //         // 'materis' => $materis,
    //         'materi' => $materi,
    //         'babs' => $babs,
    //         'judulMateri' => $judulMateri,
    //         'latihans' => $latihans,
    //         'bolehLatihan' => $bolehLatihan,
    //         'babAttempts' => $babAttempts,
    //         'semuaBabsSelesai' => $semuaBabsSelesai,
    //         'semuaBabAttemptsSelesai' => $semuaBabAttemptsSelesai
    //     ]);
    // }

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



    // public function pelajari($bab)
    // {
    //     $user = Auth::user();

    //     // Memeriksa apakah user sudah login
    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $babs = Bab::where('id', $bab)->with('materi')->first(); // Menggunakan first() atau findOrFail()

    //     if ($babs) {
    //         $materiId = $babs->materi->id;

    //         $renderedMarkdown = Markdown::convertToHtml($babs->isi);

    //         //* Lanjut Materi Selanjutnya
    //         $materiId = $babs->materi->id;

    //         $materis = Materi::findOrFail($materiId)->withCount('babs')->orderBy('id', 'asc');
    //         $nextBab = Bab::where('materi_id', $materiId)->where('id', '>', $bab)->with('materi')->orderBy('id', 'asc')->first();

    //         $babAttempts = BabAttempt::where('user_id', $user->id)
    //             ->where('bab_id', $babs->id)
    //             ->get();

    //         // dd($babAttempts);

    //         return view('frontend.user.materi.bab.pelajari', ['babs' => $babs, 'materiId' => $materiId, 'renderedMarkdown' => $renderedMarkdown, 'nextBab' => $nextBab, 'materis' => $materis, 'babAttempts' => $babAttempts]);
    //     } else {
    //         // Handle jika bab tidak ditemukan
    //         return abort(404);
    //     }
    // }

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

    // public function selesaikanMateri(int $bab)
    // {
    //     $babs = Bab::findOrFail($bab);

    //     if ($babs) {

    //         BabAttempt::insert([
    //             'bab_id' => $babs->id,
    //             'user_id' => Auth::user()->id,
    //             'status' =>  1,
    //         ]);

    //         return redirect('/user/materi/' . $bab . '/pelajari')->with('message', 'Bab Materi berhasil diselesaikan.');
    //     } else {
    //         return redirect('/user/materi/' . $bab . '/pelajari')->with('message', 'Tidak ada Bab Materi yang ditemukan.');
    //     }
    // }

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

    // public function bukaLatihan($materi)
    // {
    //     $user = Auth::user();

    //     // Memeriksa apakah user sudah login
    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $materis = Materi::findOrFail($materi)->withCount(['latihans'])->orderBy('id', 'ASC')->paginate(10);
    //     $latihans = Latihan::where('materi_id', $materi)->get();
    //     $judulMateri = $materis->firstWhere('id', $materi)->judul;

    //     // dd($materis);

    //     $latihanAttempts = LatihanAttempt::where('user_id', $user->id)
    //         ->whereIn('latihan_id', $latihans->pluck('id'))
    //         ->get();

    //     // Check apakah semua status dalam $babs adalah 1
    //     $semuaLatihansSelesai = $latihans->every(function ($latihan) {
    //         return $latihan->status == 2;
    //     });

    //     // Untuk Latihan sesuai status babAttempts
    //     $semuaLatihanAttemptsSelesai = $latihans->every(function ($latihan) use ($latihanAttempts) {
    //         // Temukan BabAttempt yang sesuai dengan Bab tersebut
    //         $latihanAttempt = $latihanAttempts->where('latihan_id', $latihan->id)->first();

    //         // Periksa apakah BabAttempt ditemukan dan memiliki status 1
    //         return $latihanAttempt && $latihanAttempt->status == 2;
    //     });

    //     // dd($semuaBabAttemptsSelesai);

    //     // Jika semua status adalah 1, tampilkan latihan
    //     $bolehLatihan = $semuaLatihanAttemptsSelesai ? Latihan::where('materi_id', $materi)->get() : collect();

    //     // Mengirimkan parameter status = 2 ke tampilan blade
    //     return view('frontend.user.latihan.index', [
    //         'latihans' => $latihans,
    //         'materis' => $materis,
    //         'judulMateri' => $judulMateri,
    //         'bolehLatihan' => $bolehLatihan,
    //         'latihanAttempts' => $latihanAttempts,
    //         'semuaLatihansSelesai' => $semuaLatihansSelesai,
    //         'semuaLatihanAttemptsSelesai' => $semuaLatihanAttemptsSelesai,
    //         'status' => 2, // Menambahkan parameter status dengan nilai 2
    //     ]);
    // }

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

    // public function kerjakan($latihan)
    // {
    //     $user = Auth::user();

    //     // Memeriksa apakah user sudah login
    //     if (!$user) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     // Mengambil data latihan dan test terkait
    //     $qnaLatihan = Latihan::where('id', $latihan)->with('getTest')->get();

    //     $latihanAttemptsAnswer = LatihanAttempt::where('user_id', $user->id)
    //         ->where('latihan_id', $latihan)
    //         ->get();

    //     // Mengambil semua id dari latihanAttempt
    //     $latihanAttemptIds = $latihanAttemptsAnswer->pluck('id');

    //     // Mengambil LatihanAnswer yang memiliki latihan_attempt_id sesuai dengan latihanAttemptIds
    //     $latihanAnswers = LatihanAnswer::whereIn('latihan_attempt_id', $latihanAttemptIds)
    //         ->get();

    //     // Menambahkan latihan_attempt_id, status, dan updated_at ke dalam setiap elemen array
    //     $typedAnswers = $latihanAnswers->map(function ($answer) {
    //         $attempt = LatihanAttempt::find($answer->latihan_attempt_id);
    //         return [
    //             'latihan_attempt_id' => $answer->latihan_attempt_id,
    //             'status' => $attempt->status,
    //             'updated_at' => $attempt->updated_at,
    //             'typed_answer' => $answer->typed_answer,
    //         ];
    //     });

    //     // Mengonversi hasil ke dalam bentuk string jika diperlukan
    //     $typedAnswersString = $latihanAnswers->implode(', ');

    //     // dd($typedAnswers);

    //     $latihanAttemptsAnswer2 = LatihanAttempt::where('user_id', $user->id)->where('latihan_id', $latihan)->where('status', 2)->get();

    //     $latihanAttemptIdsBenar = $latihanAttemptsAnswer2->pluck('id');

    //     $latihanAnswer2 = LatihanAnswer::whereIn('latihan_attempt_id', $latihanAttemptIdsBenar)->get('typed_answer');

    //     // dd($latihanAnswer2);

    //     if (count($qnaLatihan) > 0) {
    //         if (count($qnaLatihan[0]['getTest']) > 0) {

    //             $qna = Test::where('latihan_id', $qnaLatihan[0]['id'])->with('question', 'jawaban')->get();

    //             // Lanjutkan dengan mengatur variabel dan melanjutkan proses
    //             $latihans = Latihan::where('id', $latihan)->with('materi')->first();

    //             $materiId = $latihans->materi->id;

    //             $nextLatihan = Latihan::where('materi_id', $materiId)->where('id', '>', $latihan)->with('materi')->orderBy('id', 'asc')->first();

    //             $materis = Materi::findOrFail($materiId)->withCount('latihans')->orderBy('id', 'asc');

    //             $latihanAttempts = LatihanAttempt::where('user_id', $user->id)->where('latihan_id', $latihans->id)->get();

    //             return view('frontend.user.latihan.kerjakan', ['success' => true, 'quiz' => $qnaLatihan, 'qna' => $qna, 'latihans' => $latihans, 'materiId' => $materiId, 'nextLatihan' => $nextLatihan, 'materis' => $materis, 'latihanAttempts' => $latihanAttempts, 'typedAnswers' => $typedAnswers, 'latihanAnswer2' => $latihanAnswer2, 'latihanAttemptsAnswer' => $latihanAttemptsAnswer]);
    //         } else {
    //             return view('frontend.user.latihan.kerjakan', ['success' => false, 'msg' => 'Latihan ini belum tersedia', 'quiz' => $qnaLatihan]);
    //         }
    //     } else {
    //         return abort(404);
    //     }
    // }

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
            $nilai = $answer->nilai;
            return [
                'pre_test_attempt_id' => $answer->pre_test_attempt_id,
                'status' => $attempt->status,
                'updated_at' => $attempt->updated_at,
                'typed_answer' => $answer->typed_answer,
                'link_github' => $answer->link_github,
                'nilai' => $nilai,
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

                $preTestAttempts = PreTestAttempt::where('user_id', $user->id)->where('pre_post_id', $pretests->id)->get();

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

    // public function jawabPreTest(Request $request)
    // {
    //     // $pre_test_attempt_id = PreTestAttempt::insertGetId([
    //     //     'pre_post_id' =>  $request->pre_post_id,
    //     //     'user_id' => Auth::user()->id,
    //     //     'link_github' => $request->link_github,
    //     // ]);

    //     $user_id = Auth::user()->id;
    //     $pre_post_id = $request->pre_post_id;
    //     $link_github = $request->link_github;

    //     // Cek apakah sudah ada data PreTestAttempt untuk user dan pre_post_id yang sesuai
    //     $preTestAttempt = PreTestAttempt::updateOrInsert(
    //         ['user_id' => $user_id, 'pre_post_id' => $pre_post_id],
    //         ['link_github' => $link_github]
    //     );

    //     // Ambil atau buat objek PreTestAttempt berdasarkan user_id dan pre_post_id
    //     $preTestAttempt = PreTestAttempt::where('user_id', $user_id)
    //         ->where('pre_post_id', $pre_post_id)
    //         ->first();

    //     $pre_test_attempt_id = $preTestAttempt->id;

    //     $qcount = count($request->q);

    //     $isAllCorrect = true;

    //     if ($qcount > 0) {
    //         for ($i = 0; $i < $qcount; $i++) {
    //             $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));

    //             $now = Carbon::now();

    //             PreTestAnswer::insert([
    //                 'pre_test_attempt_id' => $pre_test_attempt_id,
    //                 'soal_id' => $request->q[$i],
    //                 'typed_answer' => $typedAnswer,
    //                 'created_at' => $now,
    //             ]);

    //             //Todo: Check typed_answer dan answer apakah sama
    //             $isCorrect = $this->checkPreTestAnswer($request->q[$i], $typedAnswer);

    //             if (!$isCorrect) {
    //                 $isAllCorrect = false;
    //             }
    //         }
    //     }

    //     $status = $isAllCorrect ? 2 : 1;

    //     PreTestAttempt::where('id', $pre_test_attempt_id)->update([
    //         'status' => $status
    //     ]);

    //     $message = ($status == 2) ? 'Selamat Anda Berhasil Menyelesaikan Pre Test' : 'Selamat Anda Berhasil Menyelesaikan Pre Test';

    //     return Redirect::back()->with('message', $message);
    // }

    public function jawabPreTest(Request $request)
    {
        // Ambil data user dan pre_post_id dari request
        $user_id = Auth::user()->id;
        $pre_post_id = $request->pre_post_id;
        $link_github = $request->link_github;

        // Cek apakah sudah ada data PreTestAttempt untuk user dan pre_post_id yang sesuai
        $preTestAttempt = PreTestAttempt::updateOrInsert(
            ['user_id' => $user_id, 'pre_post_id' => $pre_post_id],
            ['link_github' => $link_github]
        );

        // Ambil atau buat objek PreTestAttempt berdasarkan user_id dan pre_post_id
        $preTestAttempt = PreTestAttempt::where('user_id', $user_id)
            ->where('pre_post_id', $pre_post_id)
            ->first();

        $pre_test_attempt_id = $preTestAttempt->id;

        $qcount = count($request->q);

        $isAllCorrect = true;

        if ($qcount > 0) {
            for ($i = 0; $i < $qcount; $i++) {
                $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));

                $now = Carbon::now();

                // Simpan jawaban dalam database
                PreTestAnswer::insert([
                    'pre_test_attempt_id' => $pre_test_attempt_id,
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'created_at' => $now,
                ]);

                // Periksa jawaban
                $isCorrect = $this->checkPreTestAnswer($request->q[$i], $typedAnswer);

                if ($isCorrect) {
                    // Jika jawaban benar, tambahkan nilai 10 ke dalam table pre_tests_answer
                    PreTestAnswer::where('pre_test_attempt_id', $pre_test_attempt_id)
                        ->where('soal_id', $request->q[$i])
                        ->update(['nilai' => 10]);
                } else {
                    $isAllCorrect = false;
                }
            }
        }

        // Tentukan status berdasarkan apakah semua jawaban benar
        $status = $isAllCorrect ? 2 : 1;

        // Update status pada PreTestAttempt
        PreTestAttempt::where('id', $pre_test_attempt_id)->update([
            'status' => $status
        ]);

        // Tentukan pesan berdasarkan status
        $message = ($status == 2) ? 'Selamat Anda Berhasil Menyelesaikan Pre Test' : 'Selamat Anda Berhasil Menyelesaikan Pre Test';

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

        $qnaPostTest = PrePost::where('judulPrePost', 'Post Test')->with('getPrePostTest')->get();

        $postTestAttemptsAnswer = PostTestAttempt::where('user_id', $user->id)->where('pre_post_id', 4)->get();

        // Mengambil semua id dari postTestAttempt
        $postTestAttemptIds = $postTestAttemptsAnswer->pluck('id');

        // Mengambil PreTestAnswer yang memiliki pre_test_attempt_id sesuai dengan preTestAttemptsIds
        $postTestAnswers = PostTestAnswer::whereIn('post_test_attempt_id', $postTestAttemptIds)->get();

        // Menambahkan pre_test_attempt_id, status, dan updated_at ke dalam setiap elemen array
        $typedAnswers = $postTestAnswers->map(function ($answer) {
            $attempt = PostTestAttempt::find($answer->post_test_attempt_id);
            $nilai = $answer->nilai;
            return [
                'post_test_attempt_id' => $answer->post_test_attempt_id,
                'status' => $attempt->status,
                'updated_at' => $attempt->updated_at,
                'typed_answer' => $answer->typed_answer,
                'link_github' => $answer->link_github,
                'nilai' => $nilai,
            ];
        });

        // Mengonversi hasil ke dalam bentuk string jika diperlukan
        $typedAnswersString = $postTestAnswers->implode(', ');

        $postTestAttemptsAnswer2 = PostTestAttempt::where('user_id', $user->id)->where('pre_post_id', 4)->where('status', 2)->get();

        $postTestAttemptIdsBenar = $postTestAttemptsAnswer2->pluck('id');

        $postTestAnswers2 = PostTestAnswer::whereIn('post_test_attempt_id', $postTestAttemptIdsBenar)->get('typed_answer');

        if (count($qnaPostTest) > 0) {
            if (count($qnaPostTest[0]['getPrePostTest']) > 0) {

                $qna = PrePostTest::where('pre_post_id', $qnaPostTest[0]['id'])->with('question', 'jawaban')->get();

                $posttests = PrePost::where('id', 4)->with('materi')->first();

                // dd($posttests);

                $materiId = $posttests->materi->id;

                $nextLatihan = PrePost::where('materi_id', $materiId)->where('id', '>', $posttests)->with('materi')->orderBy('id', 'asc')->first();

                $materis = Materi::findOrFail($materiId)->withCount('preposts')->orderBy('id', 'asc');

                PostTestAttempt::updateOrCreate(
                    ['user_id' => $user->id, 'pre_post_id' => $posttests->id],
                    ['created_at' => Carbon::now()]
                );

                $postTestAttempts = PostTestAttempt::where('user_id', $user->id)->where('pre_post_id', $posttests->id)->get();

                return view('frontend.user.posttest.index', [
                    'success' => true,
                    'quiz' => $qnaPostTest,
                    'qna' => $qna,
                    'posttests' => $posttests,
                    'materiId' => $materiId,
                    'nextLatihan' => $nextLatihan,
                    'materis' => $materis,
                    'postTestAttempts' => $postTestAttempts,
                    'typedAnswers' => $typedAnswers,
                    'postTestAnswer2' => $postTestAnswers2,
                    'postTestAttemptsAnswer' => $postTestAttemptsAnswer
                ]);
            } else {

                return view('frontend.user.posttest.index', [
                    'success' => false,
                    'msg' => 'Post Test ini belum tersedia',
                    'quiz' => $qnaPostTest
                ]);
            }
        } else {

            return abort(404);
        }
    }

    public function jawabPostTest(Request $request)
    {
        // Ambil data user dan pre_post_id dari request
        $user_id = Auth::user()->id;
        $pre_post_id = $request->pre_post_id;
        $link_github = $request->link_github;

        // Cek apakah sudah ada data PreTestAttempt untuk user dan pre_post_id yang sesuai
        $postTestAttempt = PostTestAttempt::updateOrInsert(
            ['user_id' => $user_id, 'pre_post_id' => $pre_post_id],
            ['link_github' => $link_github]
        );

        // Ambil atau buat objek PreTestAttempt berdasarkan user_id dan pre_post_id
        $postTestAttempt = PostTestAttempt::where('user_id', $user_id)->where('pre_post_id', $pre_post_id)->first();

        $post_test_attempt_id = $postTestAttempt->id;

        $qcount = count($request->q);

        $isAllCorrect = true;

        if ($qcount > 0) {
            for ($i = 0; $i < $qcount; $i++) {
                $typedAnswer = strtolower($request->input('ans_' . ($i + 1)));

                $now = Carbon::now();

                PostTestAnswer::insert([
                    'post_test_attempt_id' => $post_test_attempt_id,
                    'soal_id' => $request->q[$i],
                    'typed_answer' => $typedAnswer,
                    'created_at' => $now,
                ]);

                // Periksa jawaban
                $isCorrect = $this->checkPostTestAnswer($request->q[$i], $typedAnswer);

                if ($isCorrect) {
                    // Jika jawaban benar, tambahkan nilai 10 ke dalam table post_tests_answer
                    PostTestAnswer::where('post_test_attempt_id', $post_test_attempt_id)->where('soal_id', $request->q[$i])->update(['nilai' => 10]);
                } else {
                    $isAllCorrect = false;
                }
            }
        }

        // Tentukan status berdasarkan apakah semua jawaban benar
        $status = $isAllCorrect ? 2 : 1;

        // Update status pada PreTestAttempt
        PostTestAttempt::where('id', $post_test_attempt_id)->update([
            'status' => $status
        ]);

        // Tentukan pesan berdasarkan status
        $message = ($status == 2) ? 'Selamat Anda Berhasil Menyelesaikan Post Test' : 'Selamat Anda Berhasil Menyelesaikan Post Test';

        return Redirect::back()->with('message', $message);
    }

    private function checkPostTestAnswer($questionId, $typedAnswer)
    {
        $correctAnswer = Answer::where('soal_id', $questionId)->first()->answer;

        return $typedAnswer === $correctAnswer;
    }
}
