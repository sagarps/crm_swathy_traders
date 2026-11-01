<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Office extends Model
{

    use LogsActivity;

    protected $table = 'offices';
    
    protected $fillable = [
        'office_name','address','location','has_paid'
    ];

    public $timestamps = false;

 
}
