<?php

namespace App\Http\Controllers;

use App\Models\ReportLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $logs = ReportLog::with(['report:id,title', 'changedBy:id,name'])
            ->when($search, function ($q) use ($search) {
                return $q->whereHas('report', function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                })->orWhereHas('changedBy', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhere('new_status', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('logs.admin-index', [
            'logs' => $logs,
            'search' => $search,
        ]);
    }
}
