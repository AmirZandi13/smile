<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionsRequest;
use App\Http\Resources\TransactionsResource;
use App\Services\BankServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * @param BankServiceInterface $bankService
     */
    public function __construct(BankServiceInterface $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/transactions",
     *     summary="Get the user's transactions",
     *     tags={"Transaction"},
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
    public function index(TransactionsRequest $request)
    {
        $accountNumber = $request->get('account_number');
        $transactions = $this->bankService->getTransactions(\request()->user(), $accountNumber);

        return response()->json([
            'data' => TransactionsResource::collection(collect($transactions))
        ], Response::HTTP_OK);
    }
}
