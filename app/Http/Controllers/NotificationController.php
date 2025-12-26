<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $params = [
                'status'   => $request->query('status'),
                'page'     => (int) $request->query('page', 1),
                'per_page' => (int) $request->query('per_page', 10),
            ];

            $result = $this->service->getUserNotifications(
                $request->user()->id,
                $params
            );

            return response()->json([
                'status'     => 'success',
                'data'       => $result['data'],
                'pagination' => $result['pagination'],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function unreadCount(Request $request)
    {
        try {
            $count = $this->service->getUnreadCount($request->user()->id);

            return response()->json([
                'status' => 'success',
                'count'  => $count,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $this->service->markAsRead($request->user()->id, $id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Notification marked as read',
            ], 200);
        } catch (\Throwable $e) {
            $statusCode = $e->getCode() ?: 500;
            
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            $this->service->markAllAsRead($request->user()->id);

            return response()->json([
                'status'  => 'success',
                'message' => 'All notifications marked as read',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
