<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'agent_id',
        'title',
        'slug',
        'location',
        'job_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'short_description',
        'description',
        'requirements',
        'status',
        'posted_at',
        'featured',
        'expires_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'expires_at' => 'datetime',
        'featured' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(JobFavorite::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'published' && !$this->isExpired();
    }
}

