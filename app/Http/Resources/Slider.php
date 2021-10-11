<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Resizable;

class Slider extends JsonResource
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
            'image'      => Voyager::image($this->thumbnail('cropped', 'image')),
            'created_at' => $this->created_at->format('d.m.Y H:i'),
        ];
    }
}
