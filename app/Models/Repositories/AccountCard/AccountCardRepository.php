<?php

namespace App\Models\Repositories\AccountCard;

use App\Models\Account;
use App\Models\AccountCard;
use App\Models\Repositories\BaseRepository;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountCardRepository extends BaseRepository implements AccountCardRepositoryInterface
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(AccountCard::class);
    }

    /**
     * @param Account $account
     *
     * @return AccountCard
     */
    public function createAccountCard(Account $account): AccountCard
    {
        return $this->create([
            'data' => [
                'account_id' => $account->id,
                'number' => env('CARD_NUMBER_PREFIX') . ((string) rand(10000000, 99999999)) ,
                'cvv2' => rand(100, 999),
                'expire_date' => Carbon::now()->addYears(2)
            ]
        ]);
    }
}
