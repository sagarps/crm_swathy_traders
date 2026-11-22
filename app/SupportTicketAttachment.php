<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicketAttachment extends Model
{
    protected $table = 'support_ticket_attachments';

    protected $fillable = [
        'ticket_id',
        'message_id',
        'uploaded_by',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function message()
    {
        return $this->belongsTo(SupportTicketMessage::class, 'message_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 0) {
            return $bytes . ' B';
        }
        return '0 B';
    }
}

