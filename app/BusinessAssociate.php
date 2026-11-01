<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessAssociate extends Model
{
    protected $table = 'business_associate';

    protected $fillable = [
        'associate_type_id','associate_name','contact_person','contact_no','place_id','address','office_id'
    ];

    public $timestamps = false;

    public function associate_place()
    {
    	return $this->belongsTo('App\Places','place_id');
    }

    public function associate_type()
    {
    	return $this->belongsTo('App\InfluencerType','associate_type_id');
    }


}
