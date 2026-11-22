<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SupportTicket extends Model
{
    use LogsActivity;

    protected $table = 'support_tickets';

    protected $fillable = [
        'ticket_number',
        'office_id',
        'customer_id',
        'created_by',
        'category_id',
        'subject',
        'description',
        'priority',
        'status',
        'is_chargeable',
        'estimated_amount',
        'actual_amount',
        'quotation_description',
        'estimated_completion_date',
        'client_approval_status',
        'client_approved_by',
        'client_approved_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'is_chargeable' => 'boolean',
        'estimated_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'estimated_completion_date' => 'date',
        'client_approved_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Status Constants
    public const STATUS_OPEN = 'OPEN';
    public const STATUS_UNDER_REVIEW = 'UNDER_REVIEW';
    public const STATUS_WAITING_FOR_CLIENT = 'WAITING_FOR_CLIENT';
    public const STATUS_QUOTATION_SENT = 'QUOTATION_SENT';
    public const STATUS_AWAITING_APPROVAL = 'AWAITING_APPROVAL';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_IN_PROGRESS = 'IN_PROGRESS';
    public const STATUS_RESOLVED = 'RESOLVED';
    public const STATUS_CLOSED = 'CLOSED';
    public const STATUS_REJECTED = 'REJECTED';

    // Priority Constants
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    // Approval Status Constants
    public const APPROVAL_NOT_REQUIRED = 'not_required';
    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    /* ---------------- Relationships ---------------- */

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(SupportTicketCategory::class, 'category_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'client_approved_by');
    }

    public function messages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function clientVisibleMessages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')
            ->where('message_type', 'reply')
            ->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(SupportTicketAttachment::class, 'ticket_id');
    }

    public function payments()
    {
        return $this->hasMany(SupportTicketPayment::class, 'ticket_id')->orderBy('payment_date', 'desc');
    }

    public function statusHistories()
    {
        return $this->hasMany(SupportTicketStatusHistory::class, 'ticket_id')->orderBy('created_at', 'desc');
    }

    /* ---------------- Financial Helpers ---------------- */

    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getOutstandingAmountAttribute(): float
    {
        if (!$this->is_chargeable || empty($this->actual_amount)) {
            return 0.00;
        }
        $outstanding = (float) $this->actual_amount - $this->totalPaid();
        return $outstanding > 0 ? round($outstanding, 2) : 0.00;
    }

    public function getPaymentStatusAttribute(): string
    {
        if (!$this->is_chargeable) {
            return 'FREE';
        }

        if (empty($this->actual_amount) || (float) $this->actual_amount <= 0) {
            return 'NOT_BILLED';
        }

        $paid = $this->totalPaid();
        $actual = (float) $this->actual_amount;

        if ($paid >= $actual) {
            return 'PAID';
        }

        if ($paid > 0) {
            return 'PARTIALLY PAID';
        }

        return 'UNPAID';
    }

    /* ---------------- Status Badges ---------------- */

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'label-info',
            self::STATUS_UNDER_REVIEW => 'label-primary',
            self::STATUS_WAITING_FOR_CLIENT => 'label-warning',
            self::STATUS_QUOTATION_SENT, self::STATUS_AWAITING_APPROVAL => 'label-warning',
            self::STATUS_APPROVED => 'label-success',
            self::STATUS_IN_PROGRESS => 'label-primary',
            self::STATUS_RESOLVED => 'label-success',
            self::STATUS_CLOSED => 'label-default',
            self::STATUS_REJECTED => 'label-danger',
            default => 'label-default',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match (strtolower($this->priority)) {
            self::PRIORITY_URGENT => 'label-danger',
            self::PRIORITY_HIGH => 'label-warning',
            self::PRIORITY_MEDIUM => 'label-info',
            self::PRIORITY_LOW => 'label-success',
            default => 'label-default',
        };
    }
}

