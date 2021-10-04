<?php

namespace App\Http\Resources;

use App\Http\Resources\InvoiceItem as InvoiceItemResource;
use App\Http\Resources\Organization as OrganizationResource;
use App\Http\Resources\Partner as PartnerResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class Invoice extends JsonResource
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
            'id'             => $this->id,
            'status'         => $this->status,
            'total'          => $this->total,
            'services_count' => $this->services_count,
            'products_count' => $this->products_count,
            'created_at'     => $this->created_at->format('d.m.Y H:i'),
            'items'          => InvoiceItemResource::collection($this->items),
            'organization'   => new OrganizationResource($this->organization),
            'partner'        => new PartnerResource($this->partner),
        ];
    }
}
