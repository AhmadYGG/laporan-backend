<?php

namespace App\Services;

use App\Repositories\User\UserRepository;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listUsers(array $params)
    {
        $paginator = $this->repo->getAllUsers($params);

        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ];
    }

    public function getUserDetail($id)
    {
        $user = $this->repo->getUserById($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->repo->getUserById($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        $result = $this->repo->deleteUser($id);

        if (!$result) {
            abort(500, 'Failed to delete user');
        }

        return true;
    }
}
