<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends BaseModel
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
