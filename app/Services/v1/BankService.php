<?php

namespace App\Services\v1;

use App\Constants\Errors;
use App\Exceptions\CustomException;
use App\Models\Account;
use App\Models\Repositories\Account\AccountRepositoryInterface;
use App\Models\Repositories\Transaction\TransactionRepositoryInterface;
use App\Models\User;
use App\Services\BankServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class BankService implements BankServiceInterface
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param User $user
     *
     * @return mixed
     */
    public function getAccounts(User $user): mixed
    {
        return $this->accountRepository->getAccounts($user);
    }

    /**
     * @param User $user
     * @param string $accountType
     *
     * @return Account
     */
    public function storeAccount(User $user, string $accountType): Account
    {
        return $this->accountRepository->storeAccount($user, $accountType);
    }

    /**
     * @param User $user
     * @param string $originAccountNumber
     * @param string $destinationAccountNumber
     * @param string $amount
     *
     * @return mixed
     */
    public function transfer(User $user, string $originAccountNumber, string $destinationAccountNumber, string $amount): mixed
    {
        $originAccount = $this->accountRepository->getAccountByNumber($originAccountNumber);
        $destinationAccount = $this->accountRepository->getAccountByNumber($destinationAccountNumber);

        $this->checkAccountBelongTOUser($user, $originAccount);

        if ($originAccount->balance < $amount) {
            throw new CustomException(Errors::NOT_ENOUGH_BALANCE, Response::HTTP_FORBIDDEN);
        }

        $response = $this->accountRepository->transfer($originAccount, $destinationAccount, $amount);

        return $response;
    }

    /**
     * @param User $user
     * @param string $accountNumber
     *
     * @return Account
     */
    public function balance(User $user, string $accountNumber): Account
    {
        $account = $this->accountRepository->getAccountByNumber($accountNumber);

        $this->checkAccountBelongTOUser($user, $account);

        return $account;
    }

    /**
     * @param User $user
     * @param string $accountNumber
     *
     * @return mixed
     * @throws CustomException
     */
    public function getTransactions(User $user, string $accountNumber): mixed
    {
        $account = $this->accountRepository->getAccountByNumber($accountNumber);

        $this->checkAccountBelongTOUser($user, $account);

        $response = $this->transactionRepository->getTransactions($user, $account);

        return $response['items'];
    }

    /**
     * @param User $user
     * @param Account $account
     *
     * @throws CustomException
     */
    private function checkAccountBelongTOUser(User $user, Account $account): void
    {
        $checkAccount = $this->accountRepository->checkAccountBelongToUser($user, $account);

        if (!$checkAccount) {
            throw new CustomException(Errors::ACCOUNT_IS_NOT_BELONG_TO_USER, Response::HTTP_FORBIDDEN);
        }
    }
}
