<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_type',
        'recipient_id',
        'type',
        'title',
        'message',
        'job_application_id',
        'job_id',
        'is_read',
        'read_at',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function recipient()
    {
        if ($this->recipient_type === 'seeker') {
            return $this->belongsTo(Seeker::class, 'recipient_id');
        }
        return $this->belongsTo(Company::class, 'recipient_id');
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}

