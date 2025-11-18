<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'company_name',
        'role_title',
        'start_date',
        'end_date',
        'is_current',
        'achievements',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }
}

