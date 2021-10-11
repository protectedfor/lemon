<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;


class Slider extends BaseModel
{
    use Resizable;

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
