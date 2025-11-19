<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'to_email',
        'subject',
        'message',
        'status',
        'failed_count',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'failed_count' => 'integer',
    ];
}
