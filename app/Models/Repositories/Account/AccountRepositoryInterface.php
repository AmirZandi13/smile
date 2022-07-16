<?php

namespace App\Models\Repositories\Account;

use App\Models\Account;
use App\Models\User;

interface AccountRepositoryInterface
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getAccounts(User $user): mixed;

    /**
     * @param User $user
     * @param string $accountType
     *
     * @return Account
     */
    public function storeAccount(User $user, string $accountType): Account;

    /**
     * @param string $accountNumber
     *
     * @return Account
     */
    public function getAccountByNumber(string $accountNumber): Account;

    /**
     * @param User $user
     * @param Account $account
     *
     * @return bool
     */
    public function checkAccountBelongToUser(User $user, Account $account): bool;

    /**
     * @param Account $originAccount
     * @param Account $destinationAccount
     * @param string $amount
     *
     * @return bool
     */
    public function transfer(Account $originAccount, Account $destinationAccount, string $amount): bool;
}
