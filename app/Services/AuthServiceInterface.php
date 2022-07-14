<?php

namespace App\Services;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * @param User $user
     *
     * @return string
     */
    public function getAccessToken(User $user): string;
}
