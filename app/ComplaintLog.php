<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintLog extends Model
{

    protected $table = 'complaint_logs';

    public $timestamps = true;
    protected $fillable = [
        'compalint_id','complaint_status_id','action_taken','employee_detail_id','is_active','office_id'
    ];


    public function status()
    {
	    return $this->belongsTo('App\ComplaintStatus','complaint_status_id');
    }
    
}
