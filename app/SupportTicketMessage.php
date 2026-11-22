<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SupportTicketMessage extends Model
{
    use LogsActivity;

    protected $table = 'support_ticket_messages';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'message_type',
    ];

    public const TYPE_REPLY = 'reply';
    public const TYPE_INTERNAL_NOTE = 'internal_note';

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(SupportTicketAttachment::class, 'message_id');
    }

    public function isInternalNote(): bool
    {
        return $this->message_type === self::TYPE_INTERNAL_NOTE;
    }

    public function isReply(): bool
    {
        return $this->message_type === self::TYPE_REPLY;
    }
}

