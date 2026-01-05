<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Http\Requests\Report\UpdateReportStatusRequest;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function indexReportController(Request $request)
    {
        try {
            $params = [
                'search'        => $request->query('search'),
                'page'          => (int) $request->query('page', 1),
                'per_page'      => (int) $request->query('per_page', 10),
                'periode_start' => $request->query('periode_start'),
                'periode_end'   => $request->query('periode_end'),
                'user'          => $request->user(),
            ];

            $result = $this->service->indexReportService($params);

            // Return view for web requests, JSON for API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'data'       => $result['data'],
                    'pagination' => $result['pagination'],
                ], 200);
            }

            // For web, get paginated reports directly
            $user = $request->user();
            $reports = \App\Models\Report::with('user')
                ->when($user->role !== 'admin', function ($q) use ($user) {
                    return $q->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Use different view for admin
            $view = $user->role === 'admin' ? 'reports.admin-index' : 'reports.index';

            return view($view, ['reports' => $reports]);
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function createReportController(StoreReportRequest $request)
    {
        try {
            $data = $this->service->createReportService(
                $request->user(),
                $request->all()
            );

            // Return view for web requests, JSON for API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ], 201);
            }

            return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function showReportController(Request $request, $id)
    {
        try {
            $data = $this->service->showReportService(
                $request->user(),
                $id
            );

            // Return view for web requests, JSON for API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ], 200);
            }

            $user = $request->user();
            $view = $user->role === 'admin' ? 'reports.admin-show' : 'reports.show';

            return view($view, ['report' => $data]);
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateReportController(UpdateReportRequest $request, $id)
    {
        try {
            $data = $this->service->updateReportService(
                $request->user(),
                $id,
                $request->validated()
            );

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'data'   => $data,
                ], 200);
            }

            return redirect()->route('reports.show', $id)->with('success', 'Laporan berhasil diperbarui!');
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updateStatusController(UpdateReportStatusRequest $request, $id)
    {
        try {
            $data = $this->service->updateReportStatusService(
                $request->user(),
                $id,
                $request->input('status'),
                $request->input('notes')
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Report status updated successfully',
                'data'    => $data,
            ], 200);

        } catch (\Throwable $e) {
            $statusCode = $e->getCode() ?: 500;
            
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function deleteReportController(Request $request, $id)
    {
        try {
            $deleted = $this->service->deleteReportService($id);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Report deleted successfully',
                ], 200);
            }

            return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', $e->getMessage());
        }
    }
}
