<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{

    protected $table = 'role_permission';
    
    protected $fillable = [
        'permission_id','role_id'
    ];
  
   public $timestamps = false;

   public function permission()
    {
        return $this->belongsTo('App\Permission', 'permission_id');
    }

   public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }
}
