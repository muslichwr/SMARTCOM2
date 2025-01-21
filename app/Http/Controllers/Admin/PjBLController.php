<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PJBL;
use App\Models\Anggota;
use App\Models\Kelompok;
use App\Models\User;
use App\Models\Ketua;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GrahamCampbell\Markdown\Facades\Markdown;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class PJBLController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::paginate(10);

        // Panggil konfirmasi hapus di frontend menggunakan sweetalert
        $title = 'Menghapus Kelompok!';
        $text = "Anda yakin ingin menghapus kelompok?";
        confirmDelete($title, $text);

        return view('admin.pjbl.kelompok.index', ['kelompoks' => $kelompoks]);
    }

    public function create()
    {
        $users = User::all();

        return view('admin.pjbl.kelompok.create', compact('users'));
    }

    public function store(PJBL $request)
    {
        $validatedData = $request->validated();

        // Membuat Kelompok baru
        $kelompok = new Kelompok;
        $kelompok->kelompok = $validatedData['kelompok'];
        $kelompok->slug = Str::slug($validatedData['kelompok']);
        $kelompok->save();

        // Menambahkan anggota dan ketua
        if ($request->has('user_id')) {
            // Menambah anggota
            foreach ($request->user_id as $user_id) {
                Anggota::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $user_id,
                ]);
            }

            // Menambahkan ketua
            Ketua::create([
                'kelompok_id' => $kelompok->id,
                'user_id' => $request->user_id[0], // Mengambil ketua pertama dari daftar user_id
            ]);
        }

        alert()->success('Perintah Berhasil', 'Kelompok Berhasil Ditambahkan');
        return redirect('admin/pjbl/kelompok');
    }

    public function edit($kelompok)
    {
        $kelompok = Kelompok::findOrFail($kelompok);
        $users = User::all();

        return view('admin.pjbl.kelompok.edit', compact('kelompok', 'users'));
    }

    public function update(PJBL $request, $kelompok)
    {
        $validatedData = $request->validated();

        $kelompok = Kelompok::findOrFail($kelompok);
        $kelompok->kelompok = $validatedData['kelompok'];
        $kelompok->slug = Str::slug($validatedData['kelompok']);
        $kelompok->save();

        // Update anggota dan ketua
        if ($request->has('user_id')) {
            // Menghapus anggota dan ketua yang ada sebelumnya
            Anggota::where('kelompok_id', $kelompok->id)->delete();
            Ketua::where('kelompok_id', $kelompok->id)->delete();

            // Menambahkan anggota baru
            foreach ($request->user_id as $user_id) {
                Anggota::create([
                    'kelompok_id' => $kelompok->id,
                    'user_id' => $user_id,
                ]);
            }

            // Menambahkan ketua baru
            Ketua::create([
                'kelompok_id' => $kelompok->id,
                'user_id' => $request->user_id[0], // Mengambil ketua pertama dari daftar user_id
            ]);
        }

        alert()->success('Perintah Berhasil', 'Kelompok Berhasil Diperbarui');
        return redirect('admin/pjbl/kelompok');
    }

    public function destroy(Kelompok $kelompok)
    {
        // Menghapus anggota dan ketua yang terhubung dengan kelompok
        Anggota::where('kelompok_id', $kelompok->id)->delete();
        Ketua::where('kelompok_id', $kelompok->id)->delete();

        $kelompok->delete();
        alert()->success('Perintah Berhasil', 'Kelompok Berhasil Dihapus');
        return back();
    }

    public function indexAnggota()
    {
        $kelompoks = Kelompok::paginate(10);

        $title = 'Menghapus Tim Kelompok';
        $text = "Anda yakin ingin menghapus tim kelompok?";
        confirmDelete($title, $text);

        return view('admin.pjbl.anggotaKelompok.index', compact('kelompoks'));
    }

    public function getUser(Request $request)
    {
        try {
            $users = User::where('role_as', 0)->get();
            if ($users->isNotEmpty()) {
                $data = [];
                $counter = 0;
                foreach ($users as $user) {
                    $anggotaKelompok = Anggota::where([
                        'kelompok_id' => $request->kelompok_id,
                        'user_id' => $user->id
                    ])->get();

                    if ($anggotaKelompok->isEmpty()) {
                        $data[$counter] = ['id' => $user->id, 'name' => $user->name];
                        $counter++;
                    }
                }
                return response()->json(['success' => true, 'msg' => 'Anggota Kelompok Berhasil Ditampilkan!', 'data' => $data]);
            }
            return response()->json(['success' => false, 'msg' => 'Anggota Kelompok Tidak Ditemukan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'kelompok_id' => 'required|exists:kelompoks,id',
        ]);

        try {
            foreach ($request->user_ids as $qid) {
                Anggota::create([
                    'kelompok_id' => $request->kelompok_id,
                    'user_id' => $qid,
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Anggota Kelompok Berhasil Ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getAnggotaKelompok(Request $request)
    {
        try {
            $data = Anggota::with('users')->where('kelompok_id', $request->kelompok_id)->get();
            return response()->json(['success' => true, 'msg' => 'Detail Anggota Kelompok!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteAnggotaKelompok(Request $request)
    {
        try {
            Anggota::findOrFail($request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Anggota Kelompok Dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
