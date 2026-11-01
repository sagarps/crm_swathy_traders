<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnquiryItem extends Model
{
    protected $table = 'enquiry_items';

    public $timestamps = true;

    protected $fillable = [
        'enquiry_id','product_id','unit_id','unit_price','qty','total','remarks'
    ];

    public function product()
    {
    	return $this->belongsTo('App\Product','product_id');
    }
    public function unit()
    {
    	return $this->belongsTo('App\UnitMeasurement','unit_id');
    }
}
