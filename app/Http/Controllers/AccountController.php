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
     *  /**
     * @OA\Info(
     *     version="1.0",
     *     title="Example for response examples value"
     * )
     * @OA\Get(
     *     path="/api/v1/accounts",
     *     summary="Get the user's accounts",
     *     tags={"Account"},
     *     @OA\Parameter(
     *         description="Authorization token",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function index()
    {
        $accounts = $this->bankService->getAccounts(\request()->user());

        return response()->json([
            'data' => AccountsResource::collection($accounts)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/accounts",
     *     summary="Store user's account",
     *     tags={"Account"},
     *     @OA\Parameter(
     *         description="Authorization token",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Account type",
     *         in="query",
     *         name="account_type",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function store(AccountRequest $request): JsonResponse
    {
        $accountType = $request->get('account_type');

        $this->bankService->storeAccount(\request()->user(), $accountType);

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/transfer",
     *     summary="Transfer between two accounts",
     *     tags={"Account"},
     *     @OA\Parameter(
     *         description="Authorization token",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Origin account number",
     *         in="query",
     *         name="origin_account_number",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Destination account number",
     *         in="query",
     *         name="destination_account_number",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Ammount",
     *         in="query",
     *         name="amount",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/v1/balance",
     *     summary="Get the user's account's balance",
     *     tags={"Account"},
     *     @OA\Parameter(
     *         description="Authorization token",
     *         in="header",
     *         name="authorization",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         description="Account number",
     *         in="query",
     *         name="account_number",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
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
