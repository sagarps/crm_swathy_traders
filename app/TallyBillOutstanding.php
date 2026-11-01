<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TallyBillOutstanding extends Model
{
    protected $table = 'tally_bill_outstanding';
    
    protected $fillable = [
        'party_name','alias','bill_date','bill_no','bill_due_date','bill_amount','receivable','payable',
        'bill_due_date','tally_name','amount','cheque_date','instrument_no'
    ];

    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'alias');
    }

    public function followupLogs()
    {
        return $this->hasMany(CollectionFollowupLog::class, 'tally_bill_id');
    }

}
