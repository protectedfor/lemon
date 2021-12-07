<?php

namespace App\Http\Resources;

use App\Http\Resources\Invoice as InvoiceResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class Organization extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'director_name'         => $this->director_name,
            'organization_title'    => $this->organization_title,
            'inn'                   => $this->inn,
            'okpo'                  => $this->okpo,
            'address'               => $this->address,
            'account_number'        => $this->account_number,
            'bik'                   => $this->bik,
            'bank_address'          => $this->bank_address,
            'phone'                 => $this->phone,
            'created_at'            => $this->created_at->format('d.m.Y H:i'),
            'taxes'                 => $this->taxes,
            'issued_invoices_total' => $this->issued_invoices_total,
            'paid_invoices_total'   => $this->paid_invoices_total,
            'user'                  => new UserResource($this->user),
            'invoices'              => InvoiceResource::collection($this->whenLoaded('invoices')),
        ];
    }
}
