<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PjBLProyekKelompokFormRequest;
use App\Models\Anggota;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PjBLController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::paginate(10);

        $tilte = 'Menghapus Kelompok!';
        $text = "Anda yakin ingin menghapus kelompok?";
        confirmDelete($tilte, $text);

        return view('admin.proyek.kelompok.index', ['kelompoks' => $kelompoks]);
    }

    public function store(PjBLProyekKelompokFormRequest $request)
    {
        $validatedData = $request->validated();

        $kelompok = new Kelompok;
        $kelompok->kelompok = $validatedData['kelompok'];
        $kelompok->slug = Str::slug($validatedData['kelompok']);
        $kelompok->save();
        alert()->success('Perintah Berhasil', 'Kelompok Berhasil Ditambahkan');

        return redirect('admin/proyek/kelompok');
    }

    public function update(PjBLProyekKelompokFormRequest $request, $kelompok)
    {
        $validatedData = $request->validated();

        $kelompok = Kelompok::findOrFail($kelompok);

        $kelompok->kelompok = $validatedData['kelompok'];
        $kelompok->slug = Str::slug($validatedData['kelompok']);
        $kelompok->update();
        alert()->success('Perintah Berhasil', 'Kelompok Berhasil DIperbarui');

        return redirect('admin/proyek/kelompok');
    }

    public function destroy(Kelompok $kelompok)
    {
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

        return view('admin.proyek.anggotaKelompok.index', compact('kelompoks'));
    }

    public function getUser(Request $request)
    {
        try {
            // $users = User::where('role_as', 0)->select('id', 'name')->get();

            // if ($users->count() > 0) {
            //     return response()->json(['success' => true, 'msg' => 'Data Berhasil Ditampilkan!', 'data' => $users]);
            // } else {
            //     return response()->json(['success' => false, 'msg' => 'User Tidak Ditemukan!']);
            // }

            $users = User::where('role_as', 0)->get();

            if (count($users) > 0) {

                $data = [];
                $counter = 0;

                foreach ($users as $user) {
                    $anggotaKelompok = Anggota::where([
                        'kelompok_id' => $request->kelompok_id,
                        'user_id' => $user->id
                    ])->get();

                    if (count($anggotaKelompok) == 0) {
                        $data[$counter]['id'] = $user->id;
                        $data[$counter]['name'] = $user->name;
                        $counter++;
                    }

                    // dd($question);

                }
                return response()->json(['success' => true, 'msg' => 'Anggota Kelompok Berhasil Ditampilkan!', 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'msg' => 'Anggota Kelompok Tidak Ditemukan!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addUser(Request $request)
    {
        try {

            if (isset($request->user_ids)) {

                foreach ($request->user_ids as $qid) {
                    Anggota::insert([
                        'kelompok_id' => $request->kelompok_id,
                        'user_id' => $qid,
                    ]);
                }
            }

            return response()->json(['success' => true, 'msg' => 'Anggota Kelompok  Berhasil Ditambahkan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getAnggotaKelompok(Request $request)
    {
        try {
            $data = Anggota::where('kelompok_id', $request->kelompok_id)->with('users')->get();

            return response()->json(['success' => true, 'msg' => 'Detail Anggota Kelompok!', 'data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteAnggotaKelompok(Request $request)
    {
        try {

            Anggota::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Anggota Kelompok Dihapus!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
