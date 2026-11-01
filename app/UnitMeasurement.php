<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitMeasurement extends Model
{
    protected $table = 'uom';
    
    protected $fillable = [
        'unit'
    ];

    public $timestamps = false;

}
