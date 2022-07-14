<?php

namespace App\Console\Commands;

use App\Models\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use App\Services\AuthServiceInterface;
use App\Services\UserServiceInterface;
use Illuminate\Console\Command;

class GetAccessTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-token:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Access Token';

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle(UserServiceInterface $userService, AuthServiceInterface $authService): string
    {
        $testUserName = env('TEST_USER_NAME', 'smile');
        $testUserEmail = env('TEST_USER_EMAIL', 'admin@smile.com');
        $testUserPassword = env('TEST_USER_PASSWORD', '123456');

        $user = $userService->getUserByEmail($testUserEmail);

        if (! $user->exists) {
            $user = $userService->create([
                'data' => [
                    'name' => $testUserName,
                    'email' => $testUserEmail,
                    'password' => $testUserPassword
                ]
            ]);
        }

        $accessToken = $authService->getAccessToken($user);

        echo $accessToken;

        return $accessToken;
    }
}
