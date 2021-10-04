<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'partner_id',
        'status',
        'currency',
    ];

    protected $with = ['items'];

    protected $appends = ['total', 'services_count'];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return float|int
     */
    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->quantity * $item->price;
        }
        return $total;
    }

    /**
     * @return int
     */
    public function getServicesCountAttribute(): int
    {
        return $this->items->filter(function ($item) {
            return $item->type === 'service';
        })->count();
    }

    /**
     * @return int
     */
    public function getProductsCountAttribute(): int
    {
        return $this->items->filter(function ($item) {
            return $item->type === 'product';
        })->count();
    }
}
