<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{

    protected $table = 'employee_details';

    public $timestamps = true;
    protected $fillable = [
        'name','address','designation_id','contact_no','dob','is_active','office_id'
    ];

    public function designation()
    {
    	return $this->belongsTo('App\Designation','designation_id');
    }

    public function office()
    {
    	return $this->belongsTo('App\Office','office_id');
    }

}
