<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeDetail;
use App\Designation;
use App\Office;
use App\User;
use Auth;

class EmployeeController extends Controller
{
    
    public function index()
    {   
        $employees = EmployeeDetail::where('office_id','=',Auth::User()->office_id)
                                   ->orderBy('name')->get();
        
        return view('employee/index',compact('employees'));
    }

    public function create()
    {
        $office 	   = Office::where('id','=',Auth::User()->office_id)
                             ->orderBy('office_name')
                             ->get();
        $designation = Designation::all();
        
        return view('employee/create',compact('office','designation'));
    
    }

    public function store(Request $request)
    {
        $dob   = date('Y-m-d', strtotime($request->get('dob'))); 
	      $check = EmployeeDetail::where('name','=',strtoupper($request->get('name')))
                             ->where('office_id','=',Auth::User()->office_id)
	                           ->count();
	      if($check > 0){
	          return redirect('employee/index')->with('name','true');
	      }

	      $staff = EmployeeDetail::create(array( 
	    										                    'name'       	   => strtoupper($request->get('name')),
	                                            'designation_id' => $request->get('designation'),
	                                            'contact_no'     => $request->get('contact'),
	                                            'dob'        	   => $dob,
	                                            'address'    	   => $request->get('address'),
	                                            'office_id'  	   => $request->get('office'),
	                                            'is_active'  	   => ($request->get('Isactive') != NULL) ? 'Y' : 'N',
	                                         ));    
	      
	      return redirect('employee/index')->with('save','true');
    }

    public function edit($id)
    {
        $staff   	  = EmployeeDetail::find($id);
        $office 	 	= Office::where('id','=',Auth::User()->office_id)
                            ->orderBy('office_name')
                            ->get();
        $designation = Designation::all();

      	return view('employee/edit',compact('staff','designation','office'));
    }

    public function updateData(Request $request)
    {
        $dob   = date('Y-m-d', strtotime($request->get('dob'))); 
        $check = EmployeeDetail::where('id','!=',$request->get('staff_id'))
                               ->where('name','=',strtoupper($request->get('name')))
                               ->where('office_id','=',Auth::User()->office_id)
                               ->count();
        if($check > 0){
            return redirect('employee/index')->with('name','true');
        }

        $staff = EmployeeDetail::where('id','=',$request->get('staff_id'))
                               ->update(array( 
                               					      'name'       	   => strtoupper($request->get('name')),
	                                            'designation_id' => $request->get('designation'),
	                                            'contact_no'     => $request->get('contact'),
	                                            'dob'        	   => $dob,
	                                            'address'    	   => $request->get('address'),
	                                            'office_id'  	   => $request->get('office'),
	                                            'is_active'  	   => ($request->get('Isactive') != NULL) ? 'Y' : 'N',
	                                        ));

        $detais = EmployeeDetail::where('id','=',$request->get('staff_id'))
                                ->first();

        if($detais->is_active == 'N'){
            User::where('employee_id','=',$detais->id)
                ->update(['is_active' => 'N']);
        }
        
        return redirect('employee/index')->with('Update','true');                          

    }
}