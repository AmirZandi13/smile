<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Requests\BalanceRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\AccountsResource;
use App\Http\Resources\BalanceResource;
use App\Services\BankServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @param BankServiceInterface $bankService
     */
    public function __construct(BankServiceInterface $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * Display the resources.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $accounts = $this->bankService->getAccounts(\request()->user());

        return response()->json([
            'data' => AccountsResource::collection($accounts)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(AccountRequest $request): JsonResponse
    {
        $accountType = $request->get('account_type');

        $this->bankService->storeAccount(\request()->user(), $accountType);

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * Transfer.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $originAccountNumber = $request->get('origin_account_number');
        $destinationAccountNumber = $request->get('destination_account_number');
        $amount = $request->get('amount');

        $this->bankService->transfer(\request()->user(), $originAccountNumber, $destinationAccountNumber, $amount);

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Transfer.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function balance(BalanceRequest $request): JsonResponse
    {
        $accountNumber = $request->get('account_number');

        $account = $this->bankService->balance(\request()->user(), $accountNumber);

        return response()->json([
            'data' => new BalanceResource($account)
        ], Response::HTTP_OK);
    }
}
