<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\EmployeeDetail;
use App\UserRole;

use Auth;

class UserController extends Controller
{

    public function index()
    {  
        $users = User::where('office_id','=',Auth::user()->office_id)
                     ->orderBy('username')
                     ->get();
    
   	    return view('users/index',compact('users'));
    }

    public function create()
    {   
        $emp_ids = User::where('is_active','=','Y')
                       ->where('office_id','=',Auth::user()->office_id)
                       ->where('employee_id','!=',null)
                       ->pluck('employee_id');
        $employe = EmployeeDetail::whereNotin('id',$emp_ids)
                                 ->where('office_id','=',Auth::user()->office_id)
                                 ->where('is_active','=','Y')
                                 ->get();
        $role    = Role::where('office_id','=',Auth::user()->office_id)
                       ->orderBy('name')
                       ->get();

        return view('users/create',compact('employe','role'));
    }

    public function store(Request $request)
    {
        $uname      = User::where('username','=',$request->get('username'))
                          ->where('office_id','=',Auth::user()->office_id)
                          ->count();
        $name       = User::where('employee_id','=',$request->get('employee'))
                          ->where('office_id','=',Auth::user()->office_id)
                          ->count();

        if($name > 0 || $uname > 0)
        {
            return redirect('user/index')->with('exist','true'); 
        }
      
        $user = User::create(array( 
                                    'employee_id'  => strtoupper($request->get('employee')),
                                    'username'     => $request->get('username'),
                                    'password'     => Hash::make($request->get('password')),
                                    'office_id'    => Auth::user()->office_id,
                                    'is_active'    => ($request->get('isactive') == 'Y')? 'Y' : 'N'
                                ));

        if($request->get('role') != NULL)
        {
            // dd($request->get('role'));

          UserRole::create([
                                    'user_id'  => $user->id,
                                    'role_id'  => $request->get('role') 
                                ]);
        }

        return redirect('user/index')->with('save','true');
    }

    public function edit($id)
    {
        $user    = User::find($id);
        $emp_ids = User::where('is_active','=','Y')
                       ->where('office_id','=',Auth::user()->office_id)
                       ->where('employee_id','!=',$user->employee_id)
                       ->where('employee_id','!=',null)
                       ->pluck('employee_id');
        $employe = EmployeeDetail::whereNotIn('id',$emp_ids)
                                 ->where('is_active','=','Y')
                                 ->where('office_id','=',Auth::user()->office_id)
                                 ->get();
        $role    = Role::where('office_id','=',Auth::user()->office_id)
                       ->orderBy('name')
                       ->get();
        $role_id = UserRole::where('user_id','=',$user->id)
                           ->first();
// dd($role_id->role_id);
        return view('users/edit',compact('user','employe','role','role_id'));
    }

    public function update(Request $request)
    {
        $uname      = User::where('username','=',$request->get('username'))
                          ->where('id','!=',$request->get('user_id'))
                          ->where('office_id','=',Auth::user()->office_id)
                          ->count();
        $name       = User::where('employee_id','=',$request->get('employee'))
                          ->where('id','!=',$request->get('user_id'))
                          ->where('office_id','=',Auth::user()->office_id)
                          ->count();
// dd($request->get('role'));
        if($request->get('role') != NULL)
        {
          UserRole::where('user_id','=',$request->get('user_id'))->delete();
          UserRole::firstOrCreate([
                                    'user_id'  => $request->get('user_id'),
                                    'role_id'  => $request->get('role') 
                                ]);
        }

        if($name > 0 || $uname > 0)
        {
          return redirect('user/index')->with('exist','true'); 
        }
      
        if($request->get('password') != null){

            $user = User::where('id','=',$request->get('user_id'))
                        ->update(array( 
                                        'employee_id'  => strtoupper($request->get('employee')),
                                        'username'     => $request->get('username'),
                                        'password'     => Hash::make($request->get('password')),
                                        'office_id'    => Auth::user()->office_id,
                                        'is_active'    => ($request->get('isactive') == 'Y')? 'Y' : 'N'
                                     ));
        }
        else
        {
          $user = User::where('id','=',$request->get('user_id'))
                      ->update(array( 
                                      'employee_id'  => strtoupper($request->get('employee')),
                                      'username'     => $request->get('username'),
                                      'office_id'    => Auth::user()->office_id,
                                      'is_active'    => ($request->get('isactive') == 'Y')? 'Y' : 'N'
                                   ));
        }
      
        return redirect('user/index')->with('Update','true');
    }

}
