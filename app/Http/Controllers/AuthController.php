<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        $user = null;
        $token = $this->service->LoginService($request->validated(), $user);

        if (!$token) {
            return response()->json([
                'message' => 'Login gagal. Username atau kata sandi tidak sesuai'
            ], 500);
        }

        return response()->json([
            'message' => 'Selamat datang, Anda berhasil login.',
            'role' => $user ? $user->role : null,
            'token' => $token
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $token = $this->service->RegisterService($request->validated());

            if (!$token) {
                return response()->json([
                    'message' => 'Registrasi gagal. Silakan coba lagi.'
                ], 500);
            }

            return response()->json([
                'message' => 'Registrasi berhasil. Selamat datang!',
                'token' => $token
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Registrasi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        $jwt = request()->bearerToken();
        if (!$jwt) {
            return response()->json([
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.'
            ], 401);
        }

        $payload = JWTAuth::setToken($jwt)->getPayload();

        $id = $payload->get('user_id');
        $agent = request()->userAgent();

        $this->service->LogoutService($id, $agent);

        return response()->json([
            'message' => 'Anda telah berhasil logout.'
        ], 200);
    }

    public function refresh()
    {
        $jwt = request()->bearerToken();
        if (!$jwt) {
            return response()->json([
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.'
            ], 401);
        }

        $payload = JWTAuth::setToken($jwt)->getPayload();

        $id = $payload->get('user_id');
        $agent = request()->userAgent();
        $role = $payload->get('role');

        $token = $this->service->RefreshService($id, $agent, $role);

        if (!$token) {
            return response()->json([
                'message' => 'Refresh Failed'
            ], 401);
        }

        return response()->json([
            'token' => $token
        ], 200);
    }
}
