<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password','office_id','employee_id','is_active','code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;

    public function employee()
    {
        return $this->belongsTo('App\EmployeeDetail','employee_id');
    }

    public function userpermission($permissionId)
    {
        $permissions = $this->role()->permissions->pluck('id')->all();
        return in_array($permissionId, $permissions);
    }

    public function role()
    {
        return $this->roles->first();
    }

    public function roles()
    {
        return $this->belongsToMany(
            'App\Role','user_role', 'user_id'
        );
    }

    public function userRole()
    {
        return $this->hasOne('App\UserRole','user_id');
    }
}
