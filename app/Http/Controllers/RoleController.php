<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\RolePermission;
use App\Permission;
use App\User;
use Auth;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::where('office_id','=',Auth::user()->office_id)
                     ->orderBy('name')
                     ->get();

    	  return view('role/index',compact('roles'));
    }

    public function create()
    {   
        $module      = Permission::where('is_active','=','A')
                                 ->distinct('module_name')
                                 ->selectRaw('module_name')
                                 ->orderBy('module_name')
                                 ->get();
                                         
        $permissions = Permission::where('is_active','=','A')
                                 ->orderBy('name')->get();

        return view('role/create',compact('permissions','module'));
    }

    public function store(Request $request)
    {
        if($request->get('permisssion') == NULL)
        {
          return redirect('role/create')->with('exists','true');
        }
       
        if($request->get('permisssion') != NULL)
        {
       	  $permissions = $request->get('permisssion');
          $check = Role::where('name','=',strtoupper(trim($request->get('role'))))
                       ->where('office_id','=',Auth::user()->office_id)
                       ->first();
          if($check == NULL){
            $role = Role::Create(array( 
                                        'name'          => strtoupper(trim($request->get('role'))),
                                        'display_name'  => strtoupper(trim($request->get('role'))),
                                        'description'   => strtoupper(trim($request->get('description'))),
                                        'office_id'     => Auth::user()->office_id
                                    ));

         	  foreach($permissions as $permission)
         	  {
           	 	RolePermission::firstOrCreate(array( 
                                                   'permission_id'  => $permission,
           	 		                                   'role_id'        => $role->id 
                                               ));
         	  }
            return redirect('role/index')->with('save','true'); 
          }
          else{
            return redirect('role/index')->with('exist','true'); 
          }
        }

    }

    public function edit($id)
    {
        $role        = Role::find($id); 
        $list        = RolePermission::where('role_id','=',$id)->pluck('permission_id')->toArray();
        $permissions = Permission::orderBy('name')->get();
        $roles       = Role::where('office_id','=',Auth::user()->office_id)->orderBy('name')->first();
        $module      = Permission::where('is_active','=','A')
                                 ->distinct('module_name')
                                 ->selectRaw('module_name')
                                 ->orderBy('module_name')
                                 ->get();

        return view('role/edit',compact('permissions','role','list','roles','id','module'));
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        $permissions = $request->get('permisssion');

        if($request->get('permisssion') != NULL)
        {
          $check = Role::where('name','=',strtoupper(trim($request->get('role'))))
                       ->where('office_id','=',Auth::user()->office_id)
                       ->where('id','!=',$id)
                       ->first();

          if($check == NULL){
            $d               = Role::find($id); 
            $d->name         = strtoupper(trim($request->get('role')));
            $d->display_name = strtoupper(trim($request->get('role')));
            $d->description  = strtoupper(trim($request->get('description')));
            $d->office_id    = Auth::user()->office_id;
            $d->save();

            RolePermission::where('role_id','=',$d->id)->delete();
           	foreach($permissions as $permission)
           	{
           	 	RolePermission::create(array( 'permission_id'  => $permission,
           	 		                            'role_id'        => $d->id ));
           	}
            return redirect('role/index')->with('Update','true');
          }
          else{
            return redirect('role/index')->with('exist','true'); 
          }
        }
         
    }

    public function switchUser($user_id) {
      $user = User::findOrFail($user_id);
      Auth::login($user);
      return redirect()->route('home');
    }
}
