<?php

namespace App\Http\Resources;

use App\Enums\TransactionTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'origin_account_number' => $this->getOriginAccountNumber(),
            'destination_account_number' => $this->getDestinationAccountNumber(),
            'date' => $this->getDate(),
            'type' => $this->getType(),
        ];
    }

    /**
     * @return string
     */
    private function getOriginAccountNumber(): string
    {
        return $this->resource->originAccount->account_number;
    }

    /**
     * @return string
     */
    private function getDestinationAccountNumber(): string
    {
        return $this->resource->destinationAccount->account_number;
    }

    /**
     * @return string
     */
    private function getDate(): string
    {
        return $this->resource->date;
    }

    /**
     * @return string
     */
    private function getType(): string
    {
        $accountNumber = request()->get('account_number');

        if ($accountNumber == $this->resource->originAccount->account_number) {
            return TransactionTypes::WITHDRAW;
        }

        if ($accountNumber == $this->resource->destinationAccount->account_number) {
            return TransactionTypes::DEPOSIT;
        }

        return "";
    }
}
