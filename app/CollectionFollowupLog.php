<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionFollowupLog extends Model
{
    protected $table = 'collection_followup_logs';

    protected $fillable = [
        'customer_id',
        'tally_bill_id',
        'followup_date',
        'followup_time',
        'next_commit_date',
        'user_id',
        'employee_id',
        'remarks',
        'offce_id'
    ];
    public $timestamps = true;
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tallyBill()
    {
        return $this->belongsTo(TallyBillOutstanding::class, 'tally_bill_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_id');
    }

    public function office()
    {
        return $this->belongsTo(OfficeDetail::class, 'offce_id');
    }

    public function lastFollowup($customer_id)
    {
        return self::where('customer_id', $customer_id)
                    ->orderBy('followup_date', 'desc')
                    ->first();
    }

    
}
