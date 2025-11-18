<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'company_size',
        'industry',
        'website',
        'status',
        'logo_path',
        'banner_path',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'organization_type',
        'about',
        'unique_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $company) {
            if (empty($company->unique_code)) {
                $company->unique_code = static::generateUniqueCode();
            }
        });

        static::saving(function (self $company) {
            if (empty($company->unique_code)) {
                $company->unique_code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'CO' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('unique_code', $code)->exists());

        return $code;
    }
}

