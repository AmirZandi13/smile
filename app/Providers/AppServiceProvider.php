<?php

namespace App\Providers;

use App\Models\Repositories\User\UserRepository;
use App\Models\Repositories\User\UserRepositoryInterface;
use App\Services\AuthServiceInterface;
use App\Services\UserServiceInterface;
use App\Services\v1\AuthService;
use App\Services\v1\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
