<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{

    protected $table = 'sub_category';
    
    public $timestamps = false;
    protected $fillable = [
        'sub_category','category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory','category_id');
    }
}   
