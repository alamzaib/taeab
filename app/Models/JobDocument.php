<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'type',
        'title',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }
}

