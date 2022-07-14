<?php

namespace App\Services\v1;

use App\Models\User;
use App\Services\AuthServiceInterface;

class AuthService implements AuthServiceInterface
{
    /**
     * @param User $user
     * @return string
     */
    public function getAccessToken(User $user): string
    {
        $accessTokenName = env('ACCESS_TOKEN_NAME', 'smile-bank-project');

        return $user->createToken($accessTokenName)->accessToken->token;
    }
}
