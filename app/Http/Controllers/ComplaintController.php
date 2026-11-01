<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Office;
use App\Complaint;
use App\User;
use App\ComplaintLog;
use App\ComplaintStatus;
use App\ComplaintType;
use App\Customer;
use Auth;

class ComplaintController extends Controller
{

    public function index(Request $request)
    {  	
    	$from      = date('Y-m-1');
        if($request->get('from') != null){
            $from  = $request->get('from');
            $from  = date('Y-m-d',strtotime($from));
        }
        $to        = date('Y-m-d');
        if($request->get('to') != null){
            $to    = $request->get('to');
            $to    = date('Y-m-d',strtotime($to));
        }

        $complaint = Complaint::whereBetween('complaint_date',[$from,$to])
        					  ->orderBy('created_at','desc')
        					  ->where('is_active','=','Y')
        					  ->where('office_id','=',Auth::User()->office_id)
        					  ->get();
    
   	    return view('complaint/index',compact('complaint','from','to'));
    }

    public function create()
    {   
    	$count  = Complaint::where('office_id','=',Auth::User()->office_id)
    					   ->count();
    	$count  = $count+1;
    	$year   = date('y');

    	$type   = ComplaintType::where('is_active','=','Y')
    						   ->orderBy('type_name')
    						   ->get();
    						   
    	$status = ComplaintStatus::where('is_active','=','Y')
    						   	 ->orderBy('seq')
    						   	 ->get();

        return view('complaint/create',compact('count','year','type','status'));
    }

    public function customerList(Request $request)
    { 
        $item = Customer::where('customer_name','like','%'.$request->get('term').'%')
                        ->orWhere('customer_phone','like','%'.$request->get('term').'%')
                        ->where('office_id','=',Auth::user()->office_id)
                        ->selectRaw('customer_name,customer_phone,id')->orderBy('customer_name')
                        ->get();

        return $item;
    }

    public function customerShow(Request $request)
    {
        if( strpos( $request->get('value'), '/' ) !== false ){
            $info     = explode( '/', $request->get('value') );
            $customer = Customer::where('customer_name','=',$info[0])
                                ->where('customer_phone','=',$info[1])
                                ->where('office_id','=',Auth::user()->office_id)
                                ->first();

            return $customer;
        }
    }
    
    public function complaintTypeSave(Request $request)
    {
    	$type 	   = $request->get('type');
    	$check     = ComplaintType::where('type_name','=',strtoupper($type))
    							  ->first();
    	if($check == null){
	    	$type_name = ComplaintType::create([
	    										 'type_name' => strtoupper($type),
	    										 'is_active' => 'Y'
	    									 ]);
	    	return $type_name;
    	}
    	else{
    		return 0;
    	}
    }

    public function store(Request $request)
    {	
    	$count  	  = Complaint::where('office_id','=',Auth::User()->office_id)
    							 ->count();
    	$count  	  = $count+1;
    	$year   	  = date('y');
    	$complaint_no = $count.'/'.$year;
    	$cname        = $request->get('name');
    	$cphone       = $request->get('phone');
    	$address      = $request->get('address');
    	$date         = $request->get('date');
    	$details      = $request->get('details');
    	$type         = $request->get('type');
    	$status       = $request->get('status');
    	$action       = $request->get('action');

    	if($request->get('search') == null){
            $check2 = Customer::where('customer_phone','=',$cphone)
                              ->where('office_id','=',Auth::user()->office_id)
                              ->count();
            if($check2 > 0){
                return redirect()->route('complaint.index')->with('number','true');
            }
            $customer = Customer::firstorcreate([
                                                    'customer_name'  => strtoupper($cname),
                                                    'customer_phone' => $cphone,
                                                    'address'		 => strtoupper($address),
                                                    'office_id'      => Auth::user()->office_id
                                               ]);
            $customer_id = $customer->id;
        }
        else{
            if ( strpos( $request->get('search'), '/' ) !== false ) {
                $info     = explode( '/', $request->get('search') );
                $customer = Customer::where('customer_name','=',$info[0])
                                    ->where('customer_phone','=',$info[1])
                                    ->where('office_id','=',Auth::user()->office_id)
                                    ->first();
                $check2 = Customer::where('customer_phone','=',$cphone)
                                  ->where('id','!=',$customer->id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('complaint.index')->with('number','true');
                }
                if($customer != null){    
                    $customer->customer_name  = strtoupper($cname);              
                    $customer->customer_phone = $cphone;     
                    $customer->address 		  = strtoupper($address);     
                    $customer->office_id      = Auth::user()->office_id;
                    $customer->save();   
                }
                else{
                    $customer = Customer::firstorcreate([
                                                            'customer_name'  => strtoupper($cname),
                                                            'customer_phone' => $cphone,
                                                            'address'		 => strtoupper($address),
                                                            'office_id'      => Auth::user()->office_id
                                                       ]);
                }
            }
            else{
                $check2 = Customer::where('customer_phone','=',$cphone)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('complaint.index')->with('number','true');
                }
                $customer = Customer::firstorcreate([
                                                        'customer_name'  => strtoupper($cname),
                                                        'customer_phone' => $cphone,
                                                        'address'		 => strtoupper($address),
                                                        'office_id'      => Auth::user()->office_id
                                                  ]);
            }
            $customer_id = $customer->id;
        }

        $complaint = Complaint::create([
        								'order_no' 			  => $complaint_no,
        								'customer_id'		  => $customer_id,
        								'customer_name'		  => strtoupper($cname),
        								'customer_phone'	  => $cphone,
        								'customer_address'	  => $address,
        								'complaint_details'	  => $details,
        								'complaint_type_id'	  => $type,
        								'entered_by'		  => Auth::User()->id,
        								'complaint_date'	  => date('Y-m-d',strtotime($date)),
        								'is_active'			  => 'Y',
        								'complaint_status_id' => $status,
                                        'office_id'           => Auth::user()->office_id
        							 ]);

       	$log       = ComplaintLog::create([
       										'compalint_id'		  => $complaint->id,
       										'complaint_status_id' => $status,
       										'action_taken'		  => $action,
       										'employee_detail_id'  => Auth::User()->employee_id,
       										'is_active'			  => 'Y',
                                            'office_id'           => Auth::user()->office_id
       									]);
        
        return redirect()->route('complaint.index')->with('save','true');
    }

    public function edit($id)
    {	
    	$complaint = Complaint::find($id);
    	$type      = ComplaintType::where('is_active','=','Y')
    						   	  ->orderBy('type_name')
    						   	  ->get();
    						   
    	$status    = ComplaintStatus::where('is_active','=','Y')
    						   	 	->orderBy('seq')
    						   	 	->get();
    	$comp_stat = ComplaintLog::where('compalint_id','=',$id)
    							 ->where('is_active','=','Y')
    							 ->first();
    	$com_log   = ComplaintLog::where('compalint_id','=',$id)
    							 ->orderBy('created_at','desc')
    							 ->get();

    	return view('complaint/edit',compact('type','status','complaint','comp_stat','com_log'));
    }

    public function update(Request $request)
    {
    	$cmp_id  = $request->get('edit_id');
    	$cname   = $request->get('name');
    	$cphone  = $request->get('phone');
    	$address = $request->get('address');
    	$date    = $request->get('date');
    	$details = $request->get('details');
    	$type    = $request->get('type');
    	$status  = $request->get('status');
    	$action  = $request->get('action');

    	$complaint = Complaint::find($cmp_id);

    	if($request->get('search') == null){
            $check2 = Customer::where('customer_phone','=',$cphone)
                              ->where('id','!=',$complaint->customer_id) //exception takinf customer id directly from enquiry
                              ->where('office_id','=',Auth::user()->office_id)
                              ->count();
            if($check2 > 0){
                return redirect()->route('complaint.index')->with('number','true');
            }
            $customer = Customer::updateOrcreate([
                                                    'customer_name'  => strtoupper($cname),
                                                    'customer_phone' => $cphone,
                                                    'office_id'      => Auth::user()->office_id
                                               ],[
                                                    'address'		 => strtoupper($address),
                                               ]);
            $customer_id = $customer->id;
        }
        else{
            if ( strpos( $request->get('search'), '/' ) !== false ) {
                $info     = explode( '/', $request->get('search') );
                $customer = Customer::where('customer_name','=',$info[0])
                                    ->where('customer_phone','=',$info[1])
                                    ->where('office_id','=',Auth::user()->office_id)
                                    ->first();
                $check2 = Customer::where('customer_phone','=',$cphone)
                                  ->where('id','!=',$customer->id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('complaint.index')->with('number','true');
                }
                if($customer != null){    
                    $customer->customer_name  = strtoupper($cname);              
                    $customer->customer_phone = $cphone;   
                    $customer->address 		  = strtoupper($address);                
                    $customer->office_id      = Auth::user()->office_id;
                    $customer->save();   
                }
            }
            else{
                $check2 = Customer::where('customer_phone','=',$cphone)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('complaint.index')->with('number','true');
                }
                $customer = Customer::updateOrcreate([
                                                        'customer_name'  => strtoupper($cname),
                                                        'customer_phone' => $cphone,
                                                        'office_id'      => Auth::user()->office_id
                                                  ],[
                                                        'address'		 => strtoupper($address),
                                                  ]);
            }
            $customer_id = $customer->id;
        }

        
        $complaint->customer_id 	  	= $customer_id;
        $complaint->customer_name 	  	= strtoupper($cname);
        $complaint->customer_phone 	  	= $cphone;
        $complaint->customer_address  	= $address;
        $complaint->complaint_details 	= $details;
        $complaint->complaint_type_id   = $type;
        $complaint->complaint_status_id = $status;
        $complaint->save();

        ComplaintLog::where('compalint_id','=',$complaint->id)
        			->update(['is_active' => 'N']);
        $log       = ComplaintLog::create([
       										'compalint_id'		  => $complaint->id,
       										'complaint_status_id' => $status,
       										'action_taken'		  => $action,
       										'employee_detail_id'  => Auth::User()->employee_id,
       										'is_active'			  => 'Y',
                                            'office_id'           => Auth::user()->office_id
       									]);

        return redirect()->route('complaint.index')->with('update','true');
    }

    public function viewCoomplaint($id)
    {	
    	$complaint = Complaint::find($id);
    	$type      = ComplaintType::where('is_active','=','Y')
    						   	  ->orderBy('type_name')
    						   	  ->get();
    						   
    	$status    = ComplaintStatus::where('is_active','=','Y')
    						   	 	->orderBy('seq')
    						   	 	->get();
    	$comp_stat = ComplaintLog::where('compalint_id','=',$id)
    							 ->where('is_active','=','Y')
    							 ->first();
    	$com_log   = ComplaintLog::where('compalint_id','=',$id)
    							 ->orderBy('created_at','desc')
    							 ->get();

    	return view('complaint/view',compact('type','status','complaint','comp_stat','com_log'));
    }
}
