<?php

namespace App\Models\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     *
     * @return User
     */
    public function getUserByEmail(string $email): User;
}
