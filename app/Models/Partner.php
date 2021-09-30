<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
