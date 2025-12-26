<?php

namespace App\Services;

use App\Repositories\Auth\AuthRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function generateAccessToken($user)
    {
        $accessToken = JWTAuth::customClaims([
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
        ])->fromUser($user);

        return $accessToken;
    }


    public function generateRefreshToken($user)
    {
        $now = Carbon::now();
        $refreshTTL = config('jwt.refresh_ttl');

        $refreshToken = JWTAuth::customClaims([
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'exp' => now()->addMinutes($refreshTTL)->timestamp,
        ])->fromUser($user);

        $this->authRepository->CreateSaveToken([
            'user_id' => $user->id,
            'role' => $user->role,
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
            'refresh_token' => $refreshToken,
            'expires_at' => $now->copy()->addMinutes($refreshTTL),
        ]);

        return $refreshToken;
    }

    public function LoginService($data, &$user = null): mixed
    {
        $user = $this->authRepository->SelectUserLogin($data['email']);
        if ($user === null) {
            return false;
        }

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return false;
        }

        $this->generateRefreshToken($user);

        return $this->generateAccessToken($user);
    }

    public function RefreshService($id, $agent, $role): mixed
    {
        $token = $this->authRepository->SelectRefreshToken($id, $agent, $role);
        if (!$token) {
            return false;
        }

        if ($role === 'superadmin' || $role === 'admin') {
            $user = $this->authRepository->SelectUserById($token->user_id);
        }

        return $this->generateAccessToken($user);
    }

    public function LogoutService($id, $agent)
    {
        $this->authRepository->DeleteToken($id, $agent);
    }

    public function RegisterService(array $data): mixed
    {
        // Set default role to 'user'
        $data['role'] = 'user';

        // Create new user
        $user = $this->authRepository->createUser($data);

        // Generate tokens
        $this->generateRefreshToken($user);

        return $this->generateAccessToken($user);
    }
}
