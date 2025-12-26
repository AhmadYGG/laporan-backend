<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{
    public function getAllUsers(array $params)
    {
        $query = User::query();

        // Search functionality
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email_phone', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Filter by role if specified
        if (!empty($params['role'])) {
            $query->where('role', $params['role']);
        }

        // Pagination
        $perPage = $params['per_page'] ?? 10;
        $page = $params['page'] ?? 1;

        return $query->orderBy('created_at', 'desc')
                     ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
