<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'office_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'changes',
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
        'severity',
        'is_suspicious',
        'created_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'is_suspicious' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function entity()
    {
        return $this->morphTo(null, 'entity_type', 'entity_id');
    }

    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
