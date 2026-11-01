<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommonMaster extends Model
{
    protected $table = 'common_master';
    
    protected $fillable = [
        'name','code','parent_id','seq','is_active'
    ];

    public $timestamps = false;

    public function common()
    {
    	return $this->belongsTo('app\CommonMaster','parent_id');
    }
}
