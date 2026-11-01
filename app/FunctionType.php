<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionType extends Model
{
    protected $table = 'function_type';
    
    protected $fillable = [
        'function_type'
    ];

    public $timestamps = false;
}
