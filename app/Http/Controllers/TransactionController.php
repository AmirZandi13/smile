<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionsRequest;
use App\Http\Resources\TransactionsResource;
use App\Services\BankServiceInterface;
use Illuminate\Http\JsonResponse;
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
     * Display the resources.
     *
     * @return JsonResponse
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
