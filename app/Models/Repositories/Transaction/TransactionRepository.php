<?php

namespace App\Models\Repositories\Transaction;

use App\Exceptions\CustomException;
use App\Models\Account;
use App\Models\Repositories\AccountCard\AccountCardRepositoryInterface;
use App\Models\Repositories\AccountType\AccountTypeRepositoryInterface;
use App\Models\Repositories\BaseRepository;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    /**
     * @param Account $originAccount
     * @param Account $destinationAccount
     * @param string $amount
     *
     * @return Transaction
     */
    public function createTransaction(Account $originAccount, Account $destinationAccount, string $amount): Transaction
    {
        return $this->create([
            'data' => [
                'origin_account_id' => $originAccount->id,
                'destination_account_id' => $destinationAccount->id,
                'date' => Carbon::now(),
                'amount' => $amount
            ]
        ]);
    }

    /**
     * @param User $user
     * @param Account $account
     *
     * @return mixed
     */
    public function getTransactions(User $user, Account $account): mixed
    {
        return $this->getAll([
            'wheres' => [
                    [
                        'method' => 'where',
                        'args' => [function($query) use($account) {
                            $query->where('origin_account_id', $account->id)
                                ->orWhere('destination_account_id', $account->id);
                        }],
                    ],
                ],
        ]);
    }
}
