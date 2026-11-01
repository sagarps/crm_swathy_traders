<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityAlert extends Model
{
    protected $table = 'security_alerts';

    protected $fillable = [
        'alert_type',
        'severity',
        'title',
        'description',
        'ip_address',
        'user_id',
        'metadata',
        'status',
        'resolved_by',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'investigating']);
    }

    public function scopeCritical($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }
}
