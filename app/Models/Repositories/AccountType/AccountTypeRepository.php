<?php

namespace App\Models\Repositories\AccountType;

use App\Models\AccountType;
use App\Models\Repositories\BaseRepository;

class AccountTypeRepository extends BaseRepository implements AccountTypeRepositoryInterface
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(AccountType::class);
    }

    /**
     * @param string $type
     *
     * @return AccountType
     */
    public function getAccountTypeWithType(string $type): AccountType
    {
        return $this->getQuery('where', ['title', $type])->first() ?? new AccountType();
    }
}
