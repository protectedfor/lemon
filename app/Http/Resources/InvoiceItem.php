<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class InvoiceItem extends JsonResource
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
            'id'         => $this->id,
            'title'      => $this->title,
            'type'       => $this->type,
            'quantity'   => $this->quantity,
            'unit'       => $this->unit,
            'price'      => $this->price,
            'created_at' => $this->created_at->format('d.m.Y H:i')
        ];
    }
}
