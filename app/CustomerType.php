<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    protected $table = 'customer_types';
    
    protected $fillable = [
        'customer_type_name','office_id','created_by'
    ];

    public $timestamps = true;

    public function customers()
    {
    	return $this->hasMany('App\Customer');
    }
    
}
