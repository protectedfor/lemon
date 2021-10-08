<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'quantity',
        'unit',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
