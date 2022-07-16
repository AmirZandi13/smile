<?php

namespace App\Models\Repositories\AccountCard;

use App\Models\Account;
use App\Models\AccountCard;

interface AccountCardRepositoryInterface
{
    /**
     * @param Account $account
     *
     * @return AccountCard
     */
    public function createAccountCard(Account $account): AccountCard;
}
