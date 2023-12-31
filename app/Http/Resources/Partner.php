<?php

namespace App\Http\Resources;

use App\Http\Resources\Organization as OrganizationResource;
use App\Http\Resources\Invoice as InvoiceResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class Partner extends JsonResource
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
            'id'                 => $this->id,
            'organization_title' => $this->organization_title,
            'director_name'      => $this->director_name,
            'inn'                => $this->inn,
            'okpo'               => $this->okpo,
            'address'            => $this->address,
            'account_number'     => $this->account_number,
            'bank_address'       => $this->bank_address,
            'bik'                => $this->bik,
            'created_at'         => $this->created_at->format('d.m.Y H:i'),
            'organization'       => new OrganizationResource($this->organization),
            'invoices'           => InvoiceResource::collection($this->whenLoaded('invoices'))
        ];
    }
}
