<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $params = [
                'search'   => $request->query('search'),
                'role'     => $request->query('role'),
                'page'     => (int) $request->query('page', 1),
                'per_page' => (int) $request->query('per_page', 10),
            ];

            $result = $this->service->listUsers($params);

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

    public function show($id)
    {
        try {
            $data = $this->service->getUserDetail($id);

            return response()->json([
                'status' => 'success',
                'data'   => $data,
            ], 200);
        } catch (\Throwable $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteUser($id);

            return response()->json([
                'status'  => 'success',
                'message' => 'User berhasil dihapus',
            ], 200);
        } catch (\Throwable $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }
}
