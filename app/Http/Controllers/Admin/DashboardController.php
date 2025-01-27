<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Kelompok;
use App\Models\BabAttempt;
use App\Models\User;


class DashboardController extends Controller
{
    public function index()
    {
        $jumlahMateri = Materi::count();
        $jumlahKelompok = Kelompok::count();

        // Hitung total user yang telah menyelesaikan babs_attempt dengan status = 1
        $userCompleted = BabAttempt::where('status', 1)->distinct('user_id')->count('user_id');

        // Hitung total user yang mencoba babs_attempt
        $totalUsersAttempted = BabAttempt::distinct('user_id')->count('user_id');

        // Hitung persentase penyelesaian
        $completionPercentage = $totalUsersAttempted > 0 
            ? round(($userCompleted / $totalUsersAttempted) * 100, 2) 
            : 0;

        return view('admin.dashboard', compact(
            'jumlahMateri',
            'jumlahKelompok',
            'userCompleted',
            'totalUsersAttempted',
            'completionPercentage'
        ));
    }


}
