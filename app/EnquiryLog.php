<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnquiryLog extends Model
{
    protected $table = 'enquiry_logs';
    
    protected $fillable = [
        'enquiry_id','enquiry_status_id','status_date','status_remaks','user_id','active','office_id','other_user_id'
    ];

    public $timestamps = true;

    public function status()
    {
    	return $this->belongsTo('App\EnquiryStatus','enquiry_status_id');
    }

    public function flwDate($enqid,$statusid)
    {
    	$date = EnquiryLog::where('enquiry_id','=',$enqid)
    					  ->where('enquiry_status_id','=',$statusid)
    					  ->where('active','=','Y')
    					  ->first();

    	return date('d-m-Y',(strtotime($date->status_date)));
    }

    public function delDate($enqid,$statusid)
    {
    	$date = EnquiryLog::where('enquiry_id','=',$enqid)
    					  ->where('enquiry_status_id','=',$statusid)
    					  ->where('active','=','Y')
    					  ->first();

    	return date('d-m-Y',(strtotime($date->status_date)));
    }

    public function enquiry()
    {
        return $this->belongsTo('App\Enquiry','enquiry_id'); 
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

}
