<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $table = 'permissions';
    
    protected $fillable = [
        'name','module_name','module','flag','description','is_active'
    ];

    public $timestamps = false;

     public function rolepermission()
    {
        return $this->hasMany('App\RolePermission');
    }
}
