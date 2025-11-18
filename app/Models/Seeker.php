<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'resume_bio',
        'resume_portfolio_url',
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
        'profile_refreshed_at',
        'unique_code',
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
            'profile_refreshed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $seeker) {
            if (empty($seeker->unique_code)) {
                $seeker->unique_code = static::generateUniqueCode();
            }

            if (empty($seeker->profile_refreshed_at)) {
                $seeker->profile_refreshed_at = now();
            }
        });

        static::saving(function (self $seeker) {
            if (empty($seeker->unique_code)) {
                $seeker->unique_code = static::generateUniqueCode();
            }
        });
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

    public function educations(): HasMany
    {
        return $this->hasMany(SeekerEducation::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(SeekerExperience::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(SeekerReference::class);
    }

    public function hobbies(): HasMany
    {
        return $this->hasMany(SeekerHobby::class);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        $path = $this->normalizeStoragePath($this->profile_photo_path);

        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset('images/avatar-placeholder.svg');
    }

    public function getProfileCoverStyleAttribute(): string
    {
        $path = $this->normalizeStoragePath($this->profile_cover_path);

        if ($path) {
            return 'url(' . asset('storage/' . ltrim($path, '/')) . ') center/cover';
        }

        return 'linear-gradient(135deg,#235181,#1a3d63)';
    }

    protected function normalizeStoragePath(?string $path): ?string
    {
        return $path ? str_replace('\\', '/', $path) : null;
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'SE' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('unique_code', $code)->exists());

        return $code;
    }
}

