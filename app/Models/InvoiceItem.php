<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends BaseModel
{
    use HasFactory;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
