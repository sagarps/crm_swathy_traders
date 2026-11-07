<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerClassification extends Model
{
    protected $table = 'customer_classifications';

    protected $fillable = [
        'name',
        'created_by',
    ];

    public $timestamps = true;

    public function customers()
    {
        return $this->hasMany('App\Customer', 'customer_classification_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}

