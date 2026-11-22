<?php

namespace App\Services\Support;

use App\SupportTicket;
use App\User;
use App\Notifications\SupportTicketNotification;
use Illuminate\Support\Facades\Notification;

class SupportNotificationService
{
    /**
     * Notify when a new ticket is raised
     */
    public static function ticketCreated(SupportTicket $ticket): void
    {
        // 1. Notify creator
        if ($ticket->creator) {
            $ticket->creator->notify(new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Ticket {$ticket->ticket_number} Created",
                'message' => "Your ticket '{$ticket->subject}' has been registered.",
                'action_url' => route('support.tickets.show', $ticket->id),
                'event_type' => 'ticket_created',
            ]));
        }

        // 2. Notify all support administrators (office_id == 0)
        $admins = User::where('office_id', 0)->where('is_active', 'Y')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "New Ticket Raised: {$ticket->ticket_number}",
                'message' => "Office {$ticket->office->office_name} submitted ticket: {$ticket->subject}",
                'action_url' => route('admin.support.tickets.show', $ticket->id),
                'event_type' => 'admin_new_ticket',
            ]));
        }
    }

    /**
     * Notify when client posts a reply
     */
    public static function clientReplied(SupportTicket $ticket, string $replySnippet): void
    {
        $admins = User::where('office_id', 0)->where('is_active', 'Y')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Client Reply: {$ticket->ticket_number}",
                'message' => "{$ticket->office->office_name} replied: " . \Illuminate\Support\Str::limit($replySnippet, 80),
                'action_url' => route('admin.support.tickets.show', $ticket->id),
                'event_type' => 'client_reply',
            ]));
        }
    }

    /**
     * Notify when administrator posts a reply
     */
    public static function adminReplied(SupportTicket $ticket, string $replySnippet): void
    {
        $officeUsers = User::where('office_id', $ticket->office_id)->where('is_active', 'Y')->get();
        if ($officeUsers->isNotEmpty()) {
            Notification::send($officeUsers, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Developer Reply on {$ticket->ticket_number}",
                'message' => "Application developer replied: " . \Illuminate\Support\Str::limit($replySnippet, 80),
                'action_url' => route('support.tickets.show', $ticket->id),
                'event_type' => 'admin_reply',
            ]));
        }
    }

    /**
     * Notify when status transitions
     */
    public static function statusChanged(SupportTicket $ticket, string $oldStatus, string $newStatus): void
    {
        $officeUsers = User::where('office_id', $ticket->office_id)->where('is_active', 'Y')->get();
        if ($officeUsers->isNotEmpty()) {
            Notification::send($officeUsers, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Status Updated: {$ticket->ticket_number}",
                'message' => "Status changed to " . str_replace('_', ' ', $newStatus),
                'action_url' => route('support.tickets.show', $ticket->id),
                'event_type' => 'status_changed',
            ]));
        }
    }

    /**
     * Notify when quotation is submitted to client
     */
    public static function quotationSent(SupportTicket $ticket): void
    {
        $officeUsers = User::where('office_id', $ticket->office_id)->where('is_active', 'Y')->get();
        if ($officeUsers->isNotEmpty()) {
            Notification::send($officeUsers, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Quotation Received: {$ticket->ticket_number}",
                'message' => "A quotation of ₹" . number_format((float) $ticket->estimated_amount, 2) . " is awaiting your approval.",
                'action_url' => route('support.tickets.show', $ticket->id),
                'event_type' => 'quotation_sent',
            ]));
        }
    }

    /**
     * Notify when quotation is approved or rejected by client
     */
    public static function quotationResponded(SupportTicket $ticket, string $action): void
    {
        $admins = User::where('office_id', 0)->where('is_active', 'Y')->get();
        if ($admins->isNotEmpty()) {
            $statusWord = $action === 'approve' ? 'APPROVED' : 'REJECTED';
            Notification::send($admins, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Quotation {$statusWord}: {$ticket->ticket_number}",
                'message' => "{$ticket->office->office_name} has {$statusWord} the quotation.",
                'action_url' => route('admin.support.tickets.show', $ticket->id),
                'event_type' => 'quotation_response',
            ]));
        }
    }

    /**
     * Notify when client requests ticket reopening
     */
    public static function ticketReopened(SupportTicket $ticket, string $reason): void
    {
        $admins = User::where('office_id', 0)->where('is_active', 'Y')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new SupportTicketNotification([
                'ticket_id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'title' => "Ticket Reopening Requested: {$ticket->ticket_number}",
                'message' => "{$ticket->office->office_name} requested reopening: " . \Illuminate\Support\Str::limit($reason, 80),
                'action_url' => route('admin.support.tickets.show', $ticket->id),
                'event_type' => 'ticket_reopened',
            ]));
        }
    }
}

