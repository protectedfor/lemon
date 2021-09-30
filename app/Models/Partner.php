<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'organization_title',
        'director_name',
        'inn',
        'okpo',
        'address',
        'account_number',
        'bik',
        'bank_address'
    ];

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
