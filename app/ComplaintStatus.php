<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintStatus extends Model
{

    protected $table = 'complaint_status';

    public $timestamps = false;
    protected $fillable = [
        'status_name','seq','is_active'
    ];

    
}
