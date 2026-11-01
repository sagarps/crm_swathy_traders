<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfluencerType extends Model
{
    protected $table = 'influencer_type';
    
    protected $fillable = [
        'influencer_type'
    ];

    public $timestamps = false;
}
