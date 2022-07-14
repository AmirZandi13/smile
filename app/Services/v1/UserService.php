<?php

namespace App\Services\v1;

use App\Models\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Services\UserServiceInterface;

class UserService implements UserServiceInterface
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function getUserByEmail(string $email): User
    {
        return $this->userRepository->getUserByEmail($email);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->userRepository->create($data);
    }
}
