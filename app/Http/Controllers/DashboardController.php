<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->citizenDashboard();
    }

    private function adminDashboard()
    {
        $totalReports = Report::count();
        $completedReports = Report::where('status', 'done')->count();
        $recentReports = Report::where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', [
            'totalReports' => $totalReports,
            'completedReports' => $completedReports,
            'recentReports' => $recentReports,
        ]);
    }

    private function citizenDashboard()
    {
        $user = Auth::user();
        $totalReports = $user->reports()->count();
        $processedReports = $user->reports()
            ->whereIn('status', ['in_progress', 'done'])
            ->count();
        $reports = $user->reports()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.citizen', [
            'totalReports' => $totalReports,
            'processedReports' => $processedReports,
            'reports' => $reports,
        ]);
    }
}
