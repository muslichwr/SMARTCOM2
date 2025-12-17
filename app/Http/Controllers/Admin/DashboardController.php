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
        // ========== OPTIMIZED: Single query for basic statistics ==========
        $stats = DB::table('babs_attempt')
            ->selectRaw('
                COUNT(DISTINCT CASE WHEN status = 1 THEN user_id END) as user_completed,
                COUNT(DISTINCT user_id) as total_users_attempted
            ')
            ->first();
        
        $userCompleted = $stats->user_completed ?? 0;
        $totalUsersAttempted = $stats->total_users_attempted ?? 0;
        $completionPercentage = $totalUsersAttempted > 0 
            ? round(($userCompleted / $totalUsersAttempted) * 100, 2) 
            : 0;
        
        // Count materi & kelompok (simple counts, fast)
        $jumlahMateri = Materi::count();
        $jumlahKelompok = Kelompok::count();
            
        // ========== OPTIMIZED: Limit activities to last 100 for faster loading ==========
        $query = BabAttempt::with(['user:id,name', 'bab:id,judul,materi_id', 'bab.materi:id,judul']);
        
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
        
        // Limit to latest 100 records for performance
        $recentActivities = $query->orderBy('updated_at', 'desc')->limit(100)->get();
            
        // ========== OPTIMIZED: Single query for available years ==========
        $availableYears = DB::table('babs_attempt')
            ->selectRaw('DISTINCT YEAR(updated_at) as year')
            ->where('status', 1)
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        if (empty($availableYears)) {
            $availableYears = [Carbon::now()->year];
        }
        
        $selectedYear = (int) $request->get('chart_year', $availableYears[0] ?? Carbon::now()->year);
        
        // ========== OPTIMIZED: Single query for all 12 months data ==========
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        
        $monthlyData = DB::table('babs_attempt')
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->where('status', 1)
            ->whereYear('updated_at', $selectedYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        // Build completion data array for all 12 months
        $completionData = collect([]);
        for ($i = 1; $i <= 12; $i++) {
            $completionData->push($monthlyData[$i] ?? 0);
        }
        
        // ========== OPTIMIZED: Pie chart data (already efficient) ==========
        $materiCompletionData = DB::table('babs_attempt')
            ->join('babs', 'babs_attempt.bab_id', '=', 'babs.id')
            ->join('materis', 'babs.materi_id', '=', 'materis.id')
            ->where('babs_attempt.status', 1)
            ->select('materis.judul', DB::raw('COUNT(*) as total'))
            ->groupBy('materis.judul')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'jumlahMateri',
            'jumlahKelompok',
            'userCompleted',
            'totalUsersAttempted',
            'completionPercentage',
            'recentActivities',
            'months',
            'completionData',
            'availableYears',
            'selectedYear',
            'materiCompletionData'
        ));
    }
}
