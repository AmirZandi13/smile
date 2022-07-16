<?php

namespace App\Providers;

use App\Models\Repositories\Account\AccountRepository;
use App\Models\Repositories\Account\AccountRepositoryInterface;
use App\Models\Repositories\AccountCard\AccountCardRepository;
use App\Models\Repositories\AccountCard\AccountCardRepositoryInterface;
use App\Models\Repositories\AccountType\AccountTypeRepository;
use App\Models\Repositories\AccountType\AccountTypeRepositoryInterface;
use App\Models\Repositories\Transaction\TransactionRepository;
use App\Models\Repositories\Transaction\TransactionRepositoryInterface;
use App\Models\Repositories\User\UserRepository;
use App\Models\Repositories\User\UserRepositoryInterface;
use App\Services\AuthServiceInterface;
use App\Services\BankServiceInterface;
use App\Services\UserServiceInterface;
use App\Services\v1\AuthService;
use App\Services\v1\BankService;
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
//        Repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(AccountCardRepositoryInterface::class, AccountCardRepository::class);
        $this->app->bind(AccountTypeRepositoryInterface::class, AccountTypeRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

//        Services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(BankServiceInterface::class, BankService::class);
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
