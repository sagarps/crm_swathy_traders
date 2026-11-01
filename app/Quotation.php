<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{

    protected $table = 'quotations';

    public $timestamps = true;
    protected $fillable = [
        'quotation_no','date','price','enquiry_id','is_active','office_id'
    ];

    
}
