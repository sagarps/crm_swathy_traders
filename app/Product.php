<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';

    public $timestamps = true;
    protected $fillable = [
        'name','code','category_id','sub_category_id','image','group_id','sub_group_id','is_active'
    ];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory','category_id');
    }
}
