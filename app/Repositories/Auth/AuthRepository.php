<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;

class AuthRepository
{
    public function SelectUserById($id)
    {
        return User::find($id);
    }

    public function SelectUserLogin(string $field)
    {
        return User::where('email', $field)->first();
    }

    public function SelectRefreshToken($id, $agent, $role)
    {
        return UserToken::where('user_id', $id)
            ->where('user_agent', $agent)
            ->where('expires_at', '>=', Carbon::now())
            ->where('role', $role)
            ->first();
    }

    public function CreateSaveToken($data)
    {
        UserToken::upsert(
            [
                [
                    'user_id' => $data['user_id'],
                    'role' => $data['role'],
                    'user_agent' => $data['user_agent'],
                    'ip_address' => $data['ip_address'],
                    'refresh_token' => $data['refresh_token'],
                    'expires_at' => $data['expires_at']
                ]
            ],
            ['user_id', 'user_agent', 'ip_address', 'role'],
            ['refresh_token', 'expires_at']
        );
    }

    public function DeleteToken($id, $agent)
    {
        return UserToken::where('user_id', $id)
            ->where('user_agent', $agent)
            ->delete();
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }
}
