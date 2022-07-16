<?php

namespace App\Models\Repositories\Account;

use App\Exceptions\CustomException;
use App\Models\Account;
use App\Models\Repositories\AccountCard\AccountCardRepositoryInterface;
use App\Models\Repositories\AccountType\AccountTypeRepositoryInterface;
use App\Models\Repositories\BaseRepository;
use App\Models\Repositories\Transaction\TransactionRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Account::class);
        $this->accountCardRepository = app()->make(AccountCardRepositoryInterface::class);
        $this->accountTypeRepository = app()->make(AccountTypeRepositoryInterface::class);
        $this->transactionRepository = app()->make(TransactionRepositoryInterface::class);
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getAccounts(User $user): mixed
    {
        $accounts = $user->accounts;

        return $accounts;
    }

    /**
     * @param User $user
     * @param string $accountType
     *
     * @return Account
     */
    public function storeAccount(User $user, string $accountType): Account
    {
        DB::beginTransaction();

        try {
            $accountType = $this->accountTypeRepository->getAccountTypeWithType($accountType);

            $account = $this->create([
                'data' => [
                    'user_id' => $user->id,
                    'account_type_id' => $accountType->id,
                    'account_number' => ((string) rand(10000000, 99999999)),
                ]
            ]);

            $this->accountCardRepository->createAccountCard($account);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            throw new CustomException($e->getMessage(), 500);
        }

        return $account;
    }

    /**
     * @param string $accountNumber
     *
     * @return Account
     */
    public function getAccountByNumber(string $accountNumber): Account
    {
        return $this->getQuery('where', ['account_number', $accountNumber])->first() ?? new Account();
    }

    /**
     * @param User $user
     * @param Account $account
     *
     * @return bool
     */
    public function checkAccountBelongToUser(User $user, Account $account): bool
    {
        $userAccounts = $this->getAccounts($user);

        return (bool) $userAccounts->where('id', $account->id)->first();
    }

    /**
     * @param Account $originAccount
     * @param Account $destinationAccount
     * @param string $amount
     *
     * @return bool
     */
    public function transfer(Account $originAccount, Account $destinationAccount, string $amount): bool
    {
        DB::beginTransaction();

        try {
            $originAccount->update([
                'balance' => ((int) $originAccount->balance) - ((int) $amount)
            ]);

            $destinationAccount->update([
                'balance' => ((int) $destinationAccount->balance) + ((int) $amount)
            ]);

            $this->transactionRepository->createTransaction($originAccount, $destinationAccount, $amount);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            throw new CustomException($e->getMessage(), 500);
        }

        return true;
    }
}
