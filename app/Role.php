<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Role extends Model
{

    use LogsActivity;

    protected $table = 'roles';
    
    protected $fillable = [
        'name','display_name','description','office_id','flag'
    ];
    
    public $timestamps = true;

    public function rolepermission()
    {
        return $this->hasMany('App\RolePermission');
    }

    public function userRole()
    {
        return $this->hasMany('App\UserRole');
    }

       /**
     * get permissions related to role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            'App\Permission', 'role_permission', 'role_id' , 'permission_id'
        );
    }

     public function permission()
    {
        return $this->permissions->first();
    }

    public function office()
    {
        return $this->belongsTo('App\Office', 'office_id');
    }
}
