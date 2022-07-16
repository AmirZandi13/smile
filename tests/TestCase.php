<?php

namespace Tests;

use App\Services\AuthServiceInterface;
use App\Services\UserServiceInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:reset');
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getAccessToken()
    {
        $testUserEmail = env('TEST_USER_EMAIL', 'admin@smile.com');

        $userService = app()->make(UserServiceInterface::class);
        $authService = app()->make(AuthServiceInterface::class);

        $user = $userService->getUserByEmail($testUserEmail);

        $accessToken = $authService->getAccessToken($user);
        return $accessToken;
    }
}
