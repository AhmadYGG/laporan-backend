<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user');

        // Filter by date range
        if ($request->filled('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('recap.index', [
            'reports' => $reports,
        ]);
    }

    public function export(Request $request)
    {
        $query = Report::with('user');

        // Filter by date range
        if ($request->filled('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'rekap_laporan_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Tanggal', 'Judul', 'Lokasi', 'Pelapor', 'Status', 'Deskripsi']);

            // Data
            foreach ($reports as $index => $report) {
                fputcsv($file, [
                    $index + 1,
                    $report->created_at->format('d/m/Y H:i'),
                    $report->title,
                    $report->location,
                    $report->user->name,
                    ucfirst(str_replace('_', ' ', $report->status)),
                    $report->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
