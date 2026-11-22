<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SupportTicketNotification extends Notification
{
    use Queueable;

    public function __construct(
        public array $payload
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->payload['ticket_id'] ?? null,
            'ticket_number' => $this->payload['ticket_number'] ?? '',
            'title' => $this->payload['title'] ?? 'Ticket Notification',
            'message' => $this->payload['message'] ?? '',
            'action_url' => $this->payload['action_url'] ?? '#',
            'event_type' => $this->payload['event_type'] ?? 'ticket_event',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}

