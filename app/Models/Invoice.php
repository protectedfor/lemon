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

    protected $with = ['items', 'transactions'];

    protected $appends = [
        'total',
        'paid',
        'dept',
        'services_count',
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
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

    public function getStatusAttribute()
    {
        if ($this->paid === 0)
            return trans('invoiceOptions.statuses.unpaid');
        elseif ($this->total > $this->paid)
            return trans('invoiceOptions.statuses.partially_paid');
        else
            return trans('invoiceOptions.statuses.paid');
    }

    /**
     * @return float|int
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    /**
     * @return float|int
     */
    public function getPaidAttribute()
    {
        return $this->transactions->sum('amount');
    }

    /**
     * @return float|int
     */
    public function getDeptAttribute()
    {
        return $this->total - $this->paid;
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
