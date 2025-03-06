<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => $this->user->name,
            'from_wallet' => $this->fromWallet->code,
            'to_wallet' => $this->toWallet->code,
        ];
    }
}
