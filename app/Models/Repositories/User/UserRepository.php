<?php

namespace App\Models\Repositories\User;

use App\Models\Repositories\BaseRepository;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        $user = $this->getQuery('where', ['email', $email]);

        return $user->exists() ? $user->first() : app()->make(User::class);
    }
}
