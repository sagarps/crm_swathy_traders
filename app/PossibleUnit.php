<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PossibleUnit extends Model
{

    protected $table = 'possible_units';

    public $timestamps = true;
    protected $fillable = [
        'product_id','unit_id','base_value','ref','price'
    ];

    public function unit()
    {
    	return $this->belongsTo('App\UnitMeasurement','unit_id');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product','product_id');
    }
  	
}
