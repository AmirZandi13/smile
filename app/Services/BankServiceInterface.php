<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Account;
use App\Models\User;

interface BankServiceInterface
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
     * @param User $user
     * @param string $originAccountNumber
     * @param string $destinationAccountNumber
     * @param string $amount
     *
     * @return mixed
     */
    public function transfer(User $user, string $originAccountNumber, string $destinationAccountNumber, string $amount): mixed;

    /**
     * @param User $user
     * @param string $accountNumber
     *
     * @return Account
     */
    public function balance(User $user, string $accountNumber): Account;

    /**
     * @param User $user
     * @param string $accountNumber
     *
     * @return mixed
     * @throws CustomException
     */
    public function getTransactions(User $user, string $accountNumber): mixed;
}
