<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnquiryStatus extends Model
{
    protected $table = 'enquiry_status';
    
    protected $fillable = [
        'status_name','status_code','seq','is_active'
    ];

    public $timestamps = false;

}
