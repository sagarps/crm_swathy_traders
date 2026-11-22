<?php

namespace App\Policies;

use App\User;
use App\SupportTicket;
use App\SupportTicketAttachment;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportTicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any support tickets.
     */
    public function viewAny(User $user): bool
    {
        // Both admins and office users can access ticket lists (scoped accordingly in controller)
        return true;
    }

    /**
     * Determine whether the user can view the support ticket.
     */
    public function view(User $user, SupportTicket $ticket): bool
    {
        if ($user->isSupportAdmin()) {
            return true;
        }

        return (int) $ticket->office_id === (int) $user->office_id;
    }

    /**
     * Determine whether the user can create support tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can reply to the ticket.
     */
    public function reply(User $user, SupportTicket $ticket): bool
    {
        if ($user->isSupportAdmin()) {
            return true;
        }

        return (int) $ticket->office_id === (int) $user->office_id;
    }

    /**
     * Determine whether the user can add internal notes.
     */
    public function addInternalNote(User $user, SupportTicket $ticket): bool
    {
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can view internal notes.
     */
    public function viewInternalNotes(User $user): bool
    {
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can manage quotations (create, update quotation details).
     */
    public function manageQuotation(User $user, SupportTicket $ticket): bool
    {
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can approve or reject quotations.
     */
    public function respondQuotation(User $user, SupportTicket $ticket): bool
    {
        // Client users belonging to the ticket's office can approve or reject
        if ((int) $ticket->office_id === (int) $user->office_id) {
            return $ticket->client_approval_status === SupportTicket::APPROVAL_PENDING
                || in_array($ticket->status, [SupportTicket::STATUS_AWAITING_APPROVAL, SupportTicket::STATUS_QUOTATION_SENT]);
        }

        // Admin can also record decision on client behalf if required
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can record payments.
     */
    public function recordPayment(User $user, SupportTicket $ticket): bool
    {
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can update administrative status/priority.
     */
    public function updateAdminStatus(User $user, SupportTicket $ticket): bool
    {
        return $user->isSupportAdmin();
    }

    /**
     * Determine whether the user can request reopening of a resolved ticket.
     */
    public function requestReopen(User $user, SupportTicket $ticket): bool
    {
        if ($ticket->status !== SupportTicket::STATUS_RESOLVED && $ticket->status !== SupportTicket::STATUS_CLOSED) {
            return false;
        }

        if ($user->isSupportAdmin()) {
            return true;
        }

        return (int) $ticket->office_id === (int) $user->office_id;
    }

    /**
     * Determine whether the user can download an attachment.
     */
    public function downloadAttachment(User $user, SupportTicketAttachment $attachment): bool
    {
        $ticket = $attachment->ticket;

        // Verify basic ticket view access first
        if (!$this->view($user, $ticket)) {
            return false;
        }

        // If attachment is attached to an internal note, only support admin can download it
        if ($attachment->message && $attachment->message->isInternalNote()) {
            return $user->isSupportAdmin();
        }

        return true;
    }
}

