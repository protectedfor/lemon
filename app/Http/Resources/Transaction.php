<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'amount'           => $this->amount,
            'transaction_type' => trans('invoiceOptions.transaction_types.' . $this->transaction_type),
            'created_at'       => $this->created_at->format('d.m.Y H:i'),
        ];
    }
}
