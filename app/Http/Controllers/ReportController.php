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

            return response()->json([
                'status' => 'success',
                'data'       => $result['data'],
                'pagination' => $result['pagination'],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function createReportController(StoreReportRequest $request)
    {
        try {
            $data = $this->service->createReportService(
                $request->user(),
                $request->all()
            );

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function showReportController(Request $request, $id)
    {
        try {
            $data = $this->service->showReportService(
                $request->user(),
                $id
            );

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'status' => 'success',
                'data'   => $data,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
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

    public function deleteReportController($id)
    {
        try {
            $deleted = $this->service->deleteReportService($id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Report deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
