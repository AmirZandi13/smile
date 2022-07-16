<?php

namespace App\Models\Repositories\Transaction;

use App\Models\Account;
use App\Models\User;

interface TransactionRepositoryInterface
{
    /**
     * @param User $user
     * @param Account $account
     *
     * @return mixed
     */
    public function getTransactions(User $user, Account $account): mixed;
}
