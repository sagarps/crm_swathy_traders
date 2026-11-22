<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicketStatusHistory extends Model
{
    protected $table = 'support_ticket_status_histories';

    protected $fillable = [
        'ticket_id',
        'old_status',
        'new_status',
        'changed_by',
        'remarks',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

