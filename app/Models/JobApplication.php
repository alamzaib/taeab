<?php

namespace App\Models;

use App\Models\JobDocument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'seeker_id',
        'resume_document_id',
        'cover_letter_document_id',
        'cover_letter',
        'status',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }

    public function resumeDocument(): BelongsTo
    {
        return $this->belongsTo(JobDocument::class, 'resume_document_id');
    }

    public function coverLetterDocument(): BelongsTo
    {
        return $this->belongsTo(JobDocument::class, 'cover_letter_document_id');
    }
}

