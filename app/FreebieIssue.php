<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreebieIssue extends Model
{
    protected $table = 'freebie_issue';
    
    protected $fillable = [
        'associate_type_id','associate_name_id','contact_person','contact_no','place_id','address','freebie_type_id','freebie_value','freebie_issue_date','bar_code'
    ];

    public function associateName()
    {
    	return $this->belongsTo('App\BusinessAssociate','associate_name_id');
    }

    public function associateType()
    {
    	return $this->belongsTo('App\InfluencerType','associate_type_id');
    }

    public function freebieType()
    {
    	return $this->belongsTo('App\Freebie','freebie_type_id');
    }

}
