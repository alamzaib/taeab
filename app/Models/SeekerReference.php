<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerReference extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'name',
        'company',
        'title',
        'email',
        'phone',
        'notes',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }
}

