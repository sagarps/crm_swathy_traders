<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubGroup extends Model
{

    protected $table = 'sub_groups';
    
    public $timestamps = false;
    protected $fillable = [
        'subgroup_name','group_id'
    ];

    public function group()
    {
        return $this->belongsTo('App\Group','group_id');
    }
}
