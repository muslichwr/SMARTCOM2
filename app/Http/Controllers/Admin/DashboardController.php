<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Kelompok;
use App\Models\BabAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data statistik dasar
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
            
        // Ambil data aktivitas terbaru dengan filter dan pagination
        $query = BabAttempt::with(['user', 'bab.materi']);
        
        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal jika ada
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('updated_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('updated_at', '<=', $request->end_date);
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('updated_at', 'desc');
        
        // Pagination - DataTables handles client-side pagination, so get all data
        $recentActivities = $query->get();
            
        // Data untuk grafik penyelesaian materi per bulan (6 bulan terakhir)
        $months = collect([]);
        $completionData = collect([]);
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M'));
            
            $count = BabAttempt::where('status', 1)
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->count();
                
            $completionData->push($count);
        }

        return view('admin.dashboard', compact(
            'jumlahMateri',
            'jumlahKelompok',
            'userCompleted',
            'totalUsersAttempted',
            'completionPercentage',
            'recentActivities',
            'months',
            'completionData'
        ));
    }
}
