<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PossibleUnitHistory extends Model
{

    protected $table = 'possible_unit_history';
    
    public $timestamps = true;
    protected $fillable = [
        'product_id','unit_id','price',
    ];

}
