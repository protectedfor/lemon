<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'transaction_type',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];
}
