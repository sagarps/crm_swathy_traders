<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class SupportTicketPayment extends Model
{
    use LogsActivity;

    protected $table = 'support_ticket_payments';

    protected $fillable = [
        'ticket_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'remarks',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

