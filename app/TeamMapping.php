<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamMapping extends Model
{
    protected $table = 'team_mapping';
    
    protected $fillable = [
        'employee_id','parent_id','office_id'
    ];

    public $timestamps = false;

}
