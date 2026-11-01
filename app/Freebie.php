<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Freebie extends Model
{
    protected $table = 'freebie';
    
    protected $fillable = [
        'freebie_type'
    ];
}
