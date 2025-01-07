<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\PostTestAnswer;
use App\Models\PostTestAttempt;
use App\Models\PrePost;
use App\Models\PreTestAnswer;
use App\Models\PreTestAttempt;

class RiwayatController extends Controller
{
    public function index()
    {
        $title = 'Menghapus Test!';
        $text = "Anda yakin ingin menghapus test?";
        confirmDelete($title, $text);

        $users = User::where('role_as', 0)->paginate(5);

        return view('admin.riwayat.index', compact('users'));
    }

    public function cekProgress(int $user)
    {
        $user = User::findOrFail($user);

        $matery = Materi::withCount(['babs', 'latihans'])
            ->whereIn('judul', ['Pre Test', 'Post Test'])
            ->orderBy('id', 'ASC')
            ->paginate(10);

        $preposts = PrePost::whereIn('judulPrePost', ['Pre Test', 'Post Test'])->get();

        // dd($preposts);

        $materis = Materi::withCount(['babs', 'latihans'])->whereNotIn('judul', ['Pre Test', 'Post Test'])->orderBy('id', 'ASC')->paginate(10);

        // * Progress Bar
        foreach ($materis as $materi) {
            $materi->total = $materi->babs_count + $materi->latihans_count;

            // Mengambil status babs_attempts dengan status = 1
            $babsAttemptsStatus = DB::table('babs_attempt')
                ->join('babs', 'babs_attempt.bab_id', '=', 'babs.id')
                ->where('babs.materi_id', $materi->id)
                ->where('babs_attempt.status', 1)
                ->where('babs_attempt.user_id', $user->id)
                ->select('babs_attempt.status')
                ->get();

            // Mengambil status latihans_attempts dengan status = 2 dan parameter updated_at yang terbaru
            $latihansAttemptsStatus = DB::table('latihans_attempt')
                ->join('latihans', 'latihans_attempt.latihan_id', '=', 'latihans.id')
                ->where('latihans.materi_id', $materi->id)
                ->where('latihans_attempt.status', 2)
                ->where('latihans_attempt.user_id', $user->id)
                ->orderByDesc('latihans_attempt.updated_at')
                ->select('latihans_attempt.status')
                ->first();

            // dd($babsAttemptsStatus);

            $materi->babs_attempt_status = $babsAttemptsStatus->isNotEmpty() ? $babsAttemptsStatus : null;

            $materi->latihans_attempt_status = $latihansAttemptsStatus ? $latihansAttemptsStatus->status : null;
        }

        $statusPre = PreTestAttempt::where('user_id', $user->id)->first();
        $statusPost = PostTestAttempt::where('user_id', $user->id)->first();
        // dd($statusPre);

        return view('admin.riwayat.cek', compact(['user', 'materis', 'matery', 'statusPre', 'statusPost', 'preposts']));
    }

    public function cekDetail(int $user, int $prepost)
    {
        $userName = User::find($user);

        if (!$userName) {
            abort(404); // Atau tindakan lain yang sesuai dengan kasus Anda
        }

        $pp = PrePost::where('id', $prepost)->first();

        $preTest = PreTestAttempt::where('pre_post_id', $pp->id)->where('user_id', $user)->first();
        $answerPreTest = $preTest ? PreTestAnswer::where('pre_test_attempt_id', $preTest->id)->get() : null;

        $postTest = PostTestAttempt::where('pre_post_id', $pp->id)->where('user_id', $user)->first();
        $answerPostTest = $postTest ? PostTestAnswer::where('post_test_attempt_id', $postTest->id)->get() : null;

        // Ambil waktu pengerjaan dari PreTestAttempt atau PostTestAttempt
        $createdAt = $preTest ? $preTest->created_at : ($postTest ? $postTest->created_at : null);
        $updatedAt = $preTest ? $preTest->updated_at : ($postTest ? $postTest->updated_at : null);

        return view('admin.riwayat.detail', compact('pp', 'preTest', 'postTest', 'userName', 'answerPreTest', 'answerPostTest', 'createdAt', 'updatedAt'));
    }
}
