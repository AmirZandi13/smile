<?php

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    /**
     * @param string $email
     *
     * @return User
     */
    public function getUserByEmail(string $email): User;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): mixed;
}
