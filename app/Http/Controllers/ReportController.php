<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
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
                $request->validated() // penting!
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
                $request->user(),   // penting sesuai diagram
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
        \Log::info('DEBUG REQUEST UPDATE REPORT', [
            'all'   => $request->all(),
            'input' => $request->input(),
            'files' => $request->allFiles(),
            'hasFile_photo' => $request->hasFile('photo'),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
        ]);

        try {
            $data = $this->service->updateReportService(
                $request->user(),      // user yang login → sesuai diagram
                $id,                   // id report
                $request->validated()  // data yang sudah divalidasi
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
}
