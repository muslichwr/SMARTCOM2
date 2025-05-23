<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kelompok;
use App\Models\Anggota;
use App\Models\Ketua;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PJBLKelompokFormRequest;
use GrahamCampbell\Markdown\Facades\Markdown;
use RealRashid\SweetAlert\Facades\Alert;
use Storage;

class PJBLKelompokController extends Controller
{
    // Menampilkan daftar kelompok
    public function index()
    {
        // Ambil semua kelompok dengan relasi ketua dan anggota
        $kelompok = Kelompok::with('ketua', 'anggotas.user')->paginate(10);
        
        $title = 'Menghapus Kelompok!';
        $text = "Anda yakin ingin menghapus Kelompok?";
        confirmDelete($title, $text);
    
        return view('admin.pjbl.kelompok.index', compact('kelompok'));
    }

    // Menampilkan form untuk membuat kelompok
    public function create()
    {
        // Ambil siswa yang belum terdaftar sebagai anggota kelompok apapun dan bukan ketua kelompok
        $kelompokAnggota = Anggota::pluck('user_id')->toArray(); // Mendapatkan seluruh anggota dari semua kelompok
        $siswa = User::where('role_as', 0) // Hanya mengambil siswa
                    ->whereNotIn('id', $kelompokAnggota) // Pastikan siswa bukan anggota yang sudah terdaftar
                    ->get();
    
        return view('admin.pjbl.kelompok.create', compact('siswa'));
    }

    // Menyimpan kelompok baru dengan validasi menggunakan PJBLKelompokFormRequest
    public function store(PJBLKelompokFormRequest $request)
    {
        $validatedData = $request->validated();

        $kelompok = Kelompok::create([
            'kelompok' => $validatedData['kelompok'],
            'slug' => Str::slug($validatedData['kelompok']),
        ]);

        if ($kelompok) {
            // Menambahkan anggota dan ketua jika ada
            if ($request->has('anggotas') && count($request->anggotas) > 0) {
                foreach ($request->anggotas as $anggotaId) {
                    Anggota::create([
                        'kelompok_id' => $kelompok->id,
                        'user_id' => $anggotaId,
                    ]);
                }
            }

            if ($request->has('ketua_id')) {
                Ketua::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $request->ketua_id,
                ]);
            }

            return redirect('/admin/pjbl/kelompok')->with('success', 'Kelompok berhasil ditambahkan.');
        } else {
            return back()->withInput()->with('error', 'Gagal menambahkan kelompok.');
        }
    }

    // Menampilkan form untuk mengedit kelompok
    public function edit(string $slug)
    {
        $kelompok = Kelompok::where('slug', $slug)->firstOrFail();
        $anggotas = $kelompok->anggotas()->with('user')->get();  // Menambahkan user untuk anggota
        $kelompokAnggota = $anggotas->pluck('user_id')->toArray(); // Mendapatkan array ID anggota
        $ketua = $kelompok->ketua()->with('user')->first(); // Mendapatkan ketua
        
        // Ambil siswa yang belum terdaftar sebagai anggota kelompok ini 
        // ATAU yang sudah menjadi anggota kelompok ini (untuk tetap bisa dipilih)
        $allKelompokAnggota = Anggota::where('kelompok_id', '!=', $kelompok->id)
                                    ->pluck('user_id')
                                    ->toArray();
        
        $siswa = User::where('role_as', 0)
                    ->whereNotIn('id', $allKelompokAnggota)
                    ->orWhereIn('id', $kelompokAnggota)
                    ->get();
    
        return view('admin.pjbl.kelompok.edit', compact('kelompok', 'anggotas', 'kelompokAnggota', 'ketua', 'siswa'));
    }

    // Mengupdate kelompok dengan validasi menggunakan PJBLKelompokFormRequest
    public function update(PJBLKelompokFormRequest $request, string $slug)
    {
        $validatedData = $request->validated();
        $kelompok = Kelompok::where('slug', $slug)->firstOrFail();

        try {
            // Update data kelompok
            $kelompok->update([
                'kelompok' => $validatedData['kelompok'],
                'slug' => Str::slug($validatedData['kelompok']),
            ]);

            // Menambah atau memperbarui anggota
            if ($request->has('anggotas')) {
                // Hapus semua anggota sebelumnya
                Anggota::where('kelompok_id', $kelompok->id)->delete();

                // Tambahkan anggota baru
                foreach ($request->anggotas as $anggotaId) {
                    Anggota::create([
                        'kelompok_id' => $kelompok->id,
                        'user_id' => $anggotaId,
                    ]);
                }
            }

            // Menunjuk ketua baru jika ada
            if ($request->has('ketua_id')) {
                Ketua::where('kelompok_id', $kelompok->id)->delete();  // Menghapus ketua yang sebelumnya
                Ketua::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $request->ketua_id,
                ]);
            }

            return redirect('/admin/pjbl/kelompok')->with('message', 'Kelompok berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat update kelompok: ' . $e->getMessage());
        }
    }

    // Menampilkan halaman detail kelompok, termasuk anggota dan ketua
    public function show($slug)
    {
        $kelompok = Kelompok::where('slug', $slug)->firstOrFail();
        $ketua = Ketua::where('kelompok_id', $kelompok->id)->first();
        $anggotas = Anggota::where('kelompok_id', $kelompok->id)->get();
    
        return view('admin.pjbl.kelompok.show', compact('kelompok', 'ketua', 'anggotas'));
    }

    // Menambah anggota ke dalam kelompok
    public function addAnggota(Request $request, $slug)
    {
        $kelompok = Kelompok::where('slug', $slug)->first();
        $user = User::find($request->user_id);

        // Pastikan user belum menjadi anggota kelompok
        if ($kelompok && $user) {
            $existingAnggota = Anggota::where('kelompok_id', $kelompok->id)
                               ->where('user_id', $user->id)
                               ->first();
                               
            if (!$existingAnggota) {
                Anggota::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $user->id,
                ]);
                
                return redirect()->route('kelompok.show', $slug)->with('success', 'Anggota berhasil ditambahkan.');
            } else {
                return redirect()->route('kelompok.show', $slug)->with('error', 'User sudah menjadi anggota kelompok ini.');
            }
        }

        return redirect()->route('kelompok.show', $slug)->with('error', 'Gagal menambahkan anggota.');
    }

    // Menunjuk ketua untuk kelompok
    public function setKetua(Request $request, $slug)
    {
        $kelompok = Kelompok::where('slug', $slug)->first();
        $user = User::find($request->user_id);

        if (!$kelompok || !$user) {
            return redirect()->route('kelompok.show', $slug)->with('error', 'Kelompok atau user tidak ditemukan.');
        }
        
        // Cek apakah user sudah menjadi ketua di kelompok lain
        $existingKetua = Ketua::where('user_id', $user->id)->first();
        if ($existingKetua && $existingKetua->kelompok_id != $kelompok->id) {
            return redirect()->route('kelompok.show', $slug)
                   ->with('error', 'User ini sudah menjadi ketua di kelompok lain.');
        }
        
        // Pastikan user adalah anggota kelompok
        $isAnggota = Anggota::where('kelompok_id', $kelompok->id)
                    ->where('user_id', $user->id)
                    ->exists();
                    
        if ($isAnggota || $request->add_as_anggota) {
            // Hapus ketua sebelumnya jika ada
            Ketua::where('kelompok_id', $kelompok->id)->delete();
            
            // Jika user belum menjadi anggota tapi ada flag untuk menambahkan
            if (!$isAnggota && $request->add_as_anggota) {
                Anggota::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $user->id,
                ]);
            }

            // Menambahkan ketua baru
            Ketua::create([
                'kelompok_id' => $kelompok->id,
                'user_id' => $user->id,
            ]);
            
            return redirect()->route('kelompok.show', $slug)->with('success', 'Ketua berhasil ditunjuk.');
        }

        return redirect()->route('kelompok.show', $slug)
               ->with('error', 'User harus menjadi anggota kelompok terlebih dahulu.');
    }

    // Menghapus kelompok
    public function destroy($slug)
    {
        try {
            // Temukan kelompok berdasarkan slug
            $kelompok = Kelompok::where('slug', $slug)->firstOrFail();
    
            // Cek apakah kelompok sudah memiliki proyek yang terkait
            if ($kelompok->sintaks()->exists()) {
                alert()->warning('Perhatian!', 'Tidak dapat menghapus kelompok karena sudah memiliki proyek terkait.');
                return back();
            }
    
            // Mulai transaksi database untuk memastikan konsistensi data
            DB::beginTransaction();
            
            // Hapus anggota yang terkait dengan kelompok
            Anggota::where('kelompok_id', $kelompok->id)->delete();
    
            // Hapus ketua yang terkait dengan kelompok
            Ketua::where('kelompok_id', $kelompok->id)->delete();
    
            // Hapus kelompok itu sendiri
            $kelompok->delete();
            
            // Commit transaksi
            DB::commit();
    
            // Tampilkan pesan sukses
            alert()->success('Berhasil!', 'Kelompok berhasil dihapus.');
    
            // Kembali ke halaman sebelumnya
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Gagal!', 'Terjadi kesalahan saat menghapus kelompok: ' . $e->getMessage());
            return back();
        }
    }
}
