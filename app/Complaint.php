<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Complaint extends Model
{

    use LogsActivity;

    protected $table = 'complaints';

    public $timestamps = true;
    protected $fillable = [
        'order_no','customer_id','customer_name','customer_phone','customer_address','complaint_details','complaint_type_id','entered_by','complaint_date','is_active','complaint_status_id','office_id'
    ];

    public function customer()
    {
    	return $this->belongsTo('App\Customer','customer_id');
    }

    public function type()
    {
    	return $this->belongsTo('App\ComplaintType','complaint_type_id');
    }
    
    public function status()
    {
    	return $this->belongsTo('App\ComplaintStatus','complaint_status_id');
    }

    public function log($cmp_id)
    {
        $action = ComplaintLog::where('compalint_id','=',$cmp_id)
                              ->where('is_active','=','Y')
                              ->first();
        return $action;
    }
}
