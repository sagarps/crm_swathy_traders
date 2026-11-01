<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Office;
use App\EmployeeDetail;
use App\User;
use App\Role;
use App\UserRole;
use App\Permission;
use App\RolePermission;
use Auth;

class OfficeController extends Controller
{

    public function index()
    {  
        $office = Office::orderBy('office_name');
        if(Auth::user()->office_id != 0){
          $office = $office->where('id','=',Auth::user()->office_id);
        }
        $office = $office->get();
    
   	    return view('office/index',compact('office'));
    }

    public function create()
    {   
        return view('office/create');
    }

    public function store(Request $request)
    {
        $count = Office::where('office_name','=',$request->get('name'))->count();

        if($count > 0){
            return redirect('office/index')->with('exist','true'); 
        }

        $office = Office::create([
                                   'office_name' => strtoupper($request->get('name')),
                                   'address'     => strtoupper($request->get('address')),
                                   'location'    => strtoupper($request->get('location'))
                               ]);
        /*$staff  = EmployeeDetail::create(array( 
                                                'name'           => 'Admin',
                                                'designation_id' => 1,
                                                'office_id'      => $office->id,
                                                'is_active'      => 'Y',
                                           ));    
        $user   = User::firstOrCreate([
                                        'username'    => 'admin@'.$request->get('name'),
                                        'password'    => Hash::make('admin@test'),
                                        'office_id'   => $office->id,
                                        'employee_id' => $staff->id,
                                        'is_active'   => 'Y'
                                    ]);
        $role   = Role::Create(array( 
                                      'name'          => 'role'.$request->get('name'),
                                      'display_name'  => 'role'.$request->get('name'),
                                      'description'   => 'role'.$request->get('name'),
                                      'office_id'     => $office->id
                                  ));
        UserRole::firstOrCreate([
                                    'user_id'  => $user->id,
                                    'role_id'  => $role->id
                              ]);
        $per_id = Permission::where('flag','=','ADMIN')
                            ->where('is_active','=','A')
                            ->get();

        foreach ($per_id as $pid){
          RolePermission::firstOrCreate([
                                          'role_id'       => $role->id,
                                          'permission_id' => $pid->id
                                      ]);
        }      */                      

        return redirect('office/index')->with('save','true');
    }

    public function edit($id)
    {
        $office        = Office::find($id);
        $usercheck     = User::where('office_id','=',$office->id)
                             ->first();
        $office_users = User::where('office_id','=',$office->id)
                            ->get();
                              
        return view('office/edit',compact('office','usercheck','office_users'));
    }

    public function update(Request $request)
    {   
        $count = Office::where('office_name','=',strtoupper($request->get('name')))
                       ->where('id','!=',$request->get('office_id'))
                       ->count();

        if($count > 0){
            return redirect('office/index')->with('exist','true'); 
        }

        Office::where('id','=',$request->get('office_id'))
              ->update(array( 
                              'office_name' => strtoupper($request->get('name')),
                              'address'     => strtoupper($request->get('address')),
                              'location'    => strtoupper($request->get('location')),
                           ));

        $usercheck     = User::where('office_id','=',$request->get('office_id'))
                             ->first();

        if($usercheck == null && $request->get('submit') == 'adduser'){
            $office_id = $request->get('office_id');
            $staff     = EmployeeDetail::create(array( 
                                                      'name'           => 'Admin',
                                                      'designation_id' => 1,
                                                      'office_id'      => $office_id,
                                                      'is_active'      => 'Y',
                                                      'dob'            => date('Y-m-d'),
                                                    ));    
            $user      = User::firstOrCreate([
                                              'username'    => 'admin@'.$office_id,
                                              'password'    => Hash::make('admin@123'),
                                              'office_id'   => $office_id,
                                              'employee_id' => $staff->id,
                                              'is_active'   => 'Y',
                                              'code'        => 'admin@123'
                                           ]);
            $role      = Role::Create(array( 
                                            'name'          => 'ADMIN',
                                            'display_name'  => 'ADMIN',
                                            'description'   => 'ADMIN',
                                            'office_id'     => $office_id
                                          ));
            UserRole::firstOrCreate([
                                        'user_id'  => $user->id,
                                        'role_id'  => $role->id
                                  ]);
            $per_id    = Permission::where('flag','=','ADMIN')
                                    ->where('is_active','=','A')
                                    ->get();

            foreach ($per_id as $pid){
              RolePermission::firstOrCreate([
                                              'role_id'       => $role->id,
                                              'permission_id' => $pid->id
                                          ]);
            }
        }
        return redirect('office/index')->with('user','true'); 
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::find($id);
        $user->password = Hash::make($request->get('reset_password'));
        $user->is_active = ($request->get('is_active') == 'Y') ? 'Y' : 'N';
        $user->code = $request->get('reset_password');
        $user->save();
        return redirect()->route('office.edit', ['id' => $user->office_id])->with('reset','true');
    }

    // Admin Section
    public function indexAdmin()
    {  
        $office = Office::orderBy('office_name');
        if(Auth::user()->office_id != 0){
          $office = $office->where('id','=',Auth::user()->office_id);
        }
        $office = $office->get();
    
   	    return view('office/index_admin',compact('office'));
    }

    public function createAdmin()
    {   
        return view('office/create_admin');
    }

    public function storeAdmn(Request $request)
    {
        $count = Office::where('office_name','=',$request->get('name'))->count();

        if($count > 0){
            return redirect('admin/office/index')->with('exist','true'); 
        }

        $office = Office::create([
                                   'office_name' => strtoupper($request->get('name')),
                                   'address'     => strtoupper($request->get('address')),
                                   'location'    => strtoupper($request->get('location'))
                               ]);
        

        return redirect('admin/office/index')->with('save','true');
    }

    public function editAdmin($id)
    {
        $office        = Office::find($id);
        $usercheck     = User::where('office_id','=',$office->id)
                             ->first();
        $office_users = User::where('office_id','=',$office->id)
                            ->get();
                              
        return view('office/edit_admin',compact('office','usercheck','office_users'));
    }

    public function updateAdmin(Request $request)
    {   
        $count = Office::where('office_name','=',strtoupper($request->get('name')))
                       ->where('id','!=',$request->get('office_id'))
                       ->count();

        if($count > 0){
            return redirect('admin/office/index')->with('exist','true'); 
        }

        Office::where('id','=',$request->get('office_id'))
              ->update(array( 
                              'office_name' => strtoupper($request->get('name')),
                              'address'     => strtoupper($request->get('address')),
                              'location'    => strtoupper($request->get('location')),
                              'has_paid'    => $request->get('has_paid')
                           ));

        $usercheck     = User::where('office_id','=',$request->get('office_id'))
                             ->first();

        if($usercheck == null && $request->get('submit') == 'adduser'){
            $office_id = $request->get('office_id');
            $staff     = EmployeeDetail::create(array( 
                                                      'name'           => 'Admin',
                                                      'designation_id' => 1,
                                                      'office_id'      => $office_id,
                                                      'is_active'      => 'Y',
                                                      'dob'            => date('Y-m-d'),
                                                    ));    
            $user      = User::firstOrCreate([
                                              'username'    => 'admin@'.$office_id,
                                              'password'    => Hash::make('admin@123'),
                                              'office_id'   => $office_id,
                                              'employee_id' => $staff->id,
                                              'is_active'   => 'Y',
                                              'code'        => 'admin@123'
                                           ]);
            $role      = Role::Create(array( 
                                            'name'          => 'ADMIN',
                                            'display_name'  => 'ADMIN',
                                            'description'   => 'ADMIN',
                                            'office_id'     => $office_id
                                          ));
            UserRole::firstOrCreate([
                                        'user_id'  => $user->id,
                                        'role_id'  => $role->id
                                  ]);
            $per_id    = Permission::where('flag','=','ADMIN')
                                    ->where('is_active','=','A')
                                    ->get();

            foreach ($per_id as $pid){
              RolePermission::firstOrCreate([
                                              'role_id'       => $role->id,
                                              'permission_id' => $pid->id
                                          ]);
            }
        }
        return redirect('admin/office/index')->with('user','true'); 
    }

    public function resetPasswordAdmin(Request $request, $id)
    {
        $user = User::find($id);
        $user->password = Hash::make($request->get('reset_password'));
        $user->is_active = ($request->get('is_active') == 'Y') ? 'Y' : 'N';
        $user->code = $request->get('reset_password');
        $user->save();
        return redirect()->route('admin.office.edit', ['id' => $user->office_id])->with('reset','true');
    }

}
