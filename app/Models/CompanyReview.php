<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyReview extends Model
{
    protected $fillable = [
        'company_id',
        'seeker_id',
        'rating',
        'review',
        'company_reply',
        'replied_at',
        'edited_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'edited_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }
}
