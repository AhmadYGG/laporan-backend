<?php

namespace App\Services;

use App\Repositories\Auth\AuthRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    protected $repo;

    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(array $payload)
    {
        $payload['password'] = bcrypt($payload['password']);
        $user = $this->repo->create($payload);

        return $user;
    }

    public function login(array $credentials)
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new Exception('Invalid credentials', 401);
        }

        $user = auth('api')->user();

        return $token;
    }

    public function logout()
    {
        auth('api')->logout(true);
        return true;
    }

    public function refresh()
    {
        $refreshedToken = auth('api')->refresh(true, false);
        return [
            'token' => $refreshedToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ];
    }

    public function me()
    {
        return auth('api')->user();
    }
}
