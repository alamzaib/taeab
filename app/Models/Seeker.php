<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Seeker extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo_path',
        'profile_cover_path',
        'about',
        'skills',
        'current_salary',
        'target_salary',
        'current_company',
        'residence_country',
        'nationality',
        'date_of_birth',
        'address',
        'linkedin_url',
        'whatsapp_number',
        'resume',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
        'profile_cover_style',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(JobDocument::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(JobFavorite::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            return Storage::disk('public')->url($this->profile_photo_path);
        }

        return asset('images/avatar-placeholder.png');
    }

    public function getProfileCoverStyleAttribute(): string
    {
        if ($this->profile_cover_path && Storage::disk('public')->exists($this->profile_cover_path)) {
            return 'url(' . Storage::disk('public')->url($this->profile_cover_path) . ') center/cover';
        }

        return 'linear-gradient(135deg,#235181,#1a3d63)';
    }
}

