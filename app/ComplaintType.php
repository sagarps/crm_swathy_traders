<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{

    protected $table = 'complaint_types';

    public $timestamps = false;
    protected $fillable = [
        'type_name','is_active'
    ];

    
}
