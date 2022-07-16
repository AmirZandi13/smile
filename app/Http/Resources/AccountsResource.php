<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountsResource extends JsonResource
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
            'id' => $this->resource->id,
            'balance' => $this->resource->balance,
            'account_number' => $this->resource->account_number,
            'date_opened' => $this->resource->date_opened,
            'user' => $this->resource->user,
            'account_type' => $this->resource->accountType,
            'account_cards' => $this->resource->accountCards,
        ];
    }
}
