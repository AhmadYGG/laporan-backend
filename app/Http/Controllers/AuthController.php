<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected $service;
    protected $authRepo;

    public function __construct(AuthService $service, AuthRepository $authRepo)
    {
        $this->service = $service;
        $this->authRepo = $authRepo;
    }

    // ========================================
    // Web Authentication (Session-based)
    // ========================================

    public function loginWeb(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by email_phone
        $user = $this->authRepo->SelectUserLogin($request->email);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email/telepon atau kata sandi tidak sesuai.')->withInput();
        }

        // Login with Laravel session
        Auth::login($user, $request->has('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function registerWeb(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:50|unique:users,nik',
            'email_phone' => 'required|string|max:100|unique:users,email_phone',
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $data = $request->only(['nik', 'email_phone', 'name', 'password']);
            $data['role'] = 'user';

            $user = $this->authRepo->createUser($data);

            // Auto login after registration
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Registrasi berhasil. Selamat datang, ' . $user->name . '!');

        } catch (\Throwable $e) {
            return back()->with('error', 'Registrasi gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function logoutWeb(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    // ========================================
    // API Authentication (JWT-based) 
    // ========================================

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

    public function me()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 200);
    }
}
