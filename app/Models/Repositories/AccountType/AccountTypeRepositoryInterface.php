<?php

namespace App\Models\Repositories\AccountType;

use App\Models\AccountType;

interface AccountTypeRepositoryInterface
{
    /**
     * @param string $type
     *
     * @return AccountType
     */
    public function getAccountTypeWithType(string $type): AccountType;
}
