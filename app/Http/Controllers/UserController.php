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

            // Return view for web requests, JSON for API
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'     => 'success',
                    'data'       => $result['data'],
                    'pagination' => $result['pagination'],
                ], 200);
            }

            // For web, get paginated users directly with search
            $search = $request->query('search');
            $users = \App\Models\User::where('role', 'user')
                ->when($search, function ($q) use ($search) {
                    return $q->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%")
                            ->orWhere('email_phone', 'like', "%{$search}%");
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            return view('users.admin-index', ['users' => $users, 'search' => $search]);
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

    public function destroy(Request $request, $id)
    {
        try {
            $this->service->deleteUser($id);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'User berhasil dihapus',
                ], 200);
            }

            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $statusCode = $e->getCode() === 404 ? 404 : 500;
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], $statusCode);
            }

            return back()->with('error', $e->getMessage());
        }
    }
}
