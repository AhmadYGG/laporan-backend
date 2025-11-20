<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->service->register($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $result
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->service->login($request->validated());

            return response()->json([
                'status' => 'success',
                'token' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function logout()
    {
        $this->service->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function refresh()
    {
        $result = $this->service->refresh();

        return response()->json([
            'status' => 'success',
            'data' => $result
        ], 200);
    }

    public function me()
    {
        $user = $this->service->me();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
}
