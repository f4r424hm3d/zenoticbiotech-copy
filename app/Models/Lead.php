<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'source',
        'source_path',
        'ip_address',
        'user_agent',
        'status',
        'team_mail_sent_at',
        'lead_mail_sent_at',
        'mail_error',
    ];

    protected $casts = [
        'team_mail_sent_at' => 'datetime',
        'lead_mail_sent_at' => 'datetime',
    ];
}
