<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnquiryStatus;
use App\Enquiry;
use App\CommonMaster;
use App\Customer;
use App\EnquiryLog;
use App\ProductCategory;
use App\EnquiryItem;
use App\Product;
use App\PossibleUnit;
use App\TeamMapping;
use App\User;
use App\UserRole;
use App\FunctionType;
use App\InfluencerType;
use App\Places;
use App\BusinessAssociate;
use App\EmployeeDetail;

use Auth;

class TeamEnquiryController extends Controller
{
    
    public function index(Request $request)
    {      
        $from      = date('2025-01-01');//date('Y-m-01');
        if($request->get('from') != null){
            $from = $request->get('from');
        }
        $to        = date('Y-m-t');
        if($request->get('to') != null){
            $to = $request->get('to');
        }
        $status    = EnquiryStatus::where('is_active','=','Y')
                                  ->orderBy('seq')
                                  ->get();
        $status_id = $status->where('status_name','=','NEW LEAD')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::User()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->pluck('id');
        }
        $role = Auth::user()->userRole->role->name;
        
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                        ->where('office_id','=',Auth::user()->office_id)
                        ->whereBetween('enq_date', [$from, $to])
                        ->orderBy('created_at','desc')
                        ->get();
      
        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;
        $text      = 'team/NEWLEAD/'.$from.'/'.$to;

        return view('teamenquiry/index',compact('status','status_id','enquiry','from','to','text','role'));
    }

    public function enqLog(Request $request)
    {
        $enq_id   = $request->get('id');
        $enq_log  = EnquiryLog::join('enquiry_status','enquiry_status.id','=','enquiry_logs.enquiry_status_id')
                              ->where('enquiry_logs.enquiry_id','=',$enq_id)
                              ->orderBy('enquiry_logs.created_at','desc')
                              ->selectRaw('enquiry_logs.*,enquiry_status.status_name')
                              ->get();
        return $enq_log;
    }

    public function viewItem($id,$from,$to)
    {   
        $enquiry      = Enquiry::find($id);
        $place_id = Customer::where('id',$enquiry->customer_id)->first()->place_id;

        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                   ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                    ->get();
        $func_type    = FunctionType::all();

        $inf_type     = InfluencerType::all();
        
        $status       = EnquiryStatus::where('is_active','=','Y')
                                     ->orderBy('seq')
                                     ->get();
        $enq_log      = EnquiryLog::where('enquiry_id','=',$enquiry->id)
                                  ->orderBy('created_at','desc')
                                  ->get();
        $enq_date     = EnquiryLog::join('enquiry_status','enquiry_status.id','=','enquiry_logs.enquiry_status_id')
                                  ->where('enquiry_logs.enquiry_id','=',$enquiry->id)
                                  ->where('enquiry_logs.active','=','Y')
                                  ->selectRaw('enquiry_logs.*,enquiry_status.status_name')
                                  ->first();
        $remarks  = $enq_date->status_remaks;
        $flwdate      = null;
        $dlvdate      = null;

        if($enq_date->status_name == 'FOLLOW UP'){
            $flwdate = $enq_date->status_date;
        }
        if($enq_date->status_name == 'SALE CLOSE'){
            $dlvdate = $enq_date->status_date;
        }
        $text      = str_replace(' ', '', $enquiry->enqStatus->status_name);
        $text      = 'team/'.$text.'/'.$from.'/'.$to;

        $category  = ProductCategory::orderBy('category_name')->get();
        
        $enqpro    = EnquiryItem::join('products','products.id','=','enquiry_items.product_id')
                                ->join('uom','uom.id','=','enquiry_items.unit_id')
                                ->where('enquiry_items.enquiry_id','=',$enquiry->id)
                                ->selectRaw('enquiry_items.*,products.name,uom.unit')
                                ->orderBy('products.name')
                                ->get();

    $places  = Places::all();
    $associates = BusinessAssociate::where('associate_type_id',$enquiry->influencer_type)->get();

    $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
    ->where('designations.designation','SALES EXECUTIVE')
    ->select('employee_details.id','employee_details.name')
    ->get();

    $role = Auth::user()->userRole->role->name;
    $staff_id = NULL;
    if(!(Auth::user()->userpermission(12))) {
        $staff_id = Auth::user()->employee->id;
        // dd($staff_id);
    }
    $index = "team";

        return view('teamenquiry/viewitem',compact('place_id','type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','text','from','to','category','enqpro','remarks','func_type','inf_type','places','associates','employee','staff_id','index'));
    }

    public function newLead(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','NEW LEAD')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->where('office_id','=',Auth::user()->office_id)
                         ->pluck('id');
        }

        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        $text      = 'team/NEWLEAD/'.$from.'/'.$to;

        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;

        return view('teamenquiry/index',compact('status','status_id','enquiry','from','to','text','role'));
    }

    public function followUP(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','FOLLOW UP')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->where('office_id','=',Auth::user()->office_id)
                         ->pluck('id');
        }

        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereBetween('enquiry_logs.status_date', [$from, $to])
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get();   
                         
        $text      = 'team/FOLLOWUP/'.$from.'/'.$to;
        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;
        
        return view('teamenquiry/followupindex',compact('status','status_id','enquiry','from','to','text','role'));
    }

    public function saleClose(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','SALE CLOSE')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->where('office_id','=',Auth::user()->office_id)
                         ->pluck('id');
        }

        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereDate('enquiry_logs.created_at','>=',$from)
                            ->whereDate('enquiry_logs.created_at','<=',$to)
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get();

        $text      = 'team/SALECLOSE/'.$from.'/'.$to;

        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;
               
        return view('teamenquiry/salecloseindex',compact('status','status_id','enquiry','from','to','text','role'));
    }

    public function cancel(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','CANCEL')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->where('office_id','=',Auth::user()->office_id)
                         ->pluck('id');
        }

        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        $text      = '/CANCEL/'.$from.'/'.$to;
        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;

        $enquiry_list   = Enquiry::where('user_id','=',Auth::user()->id)
                    ->where('office_id','=',Auth::user()->office_id)
                    ->whereBetween('enq_date', [$from, $to])
                    ->orderBy('created_at','desc')
                    ->get();
        
        return view('teamenquiry/cancelindex',compact('status','status_id','enquiry','from','to','text','role','enquiry_list'));
    }

    public function lost(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','LOST')
                            ->first()->id;

        $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $length    = count($emp_ids); 
        $emp_ids->offsetSet($length,Auth::user()->employee_id);

        $users     = null;
        if($emp_ids != null){
            $users = User::whereIn('employee_id',$emp_ids)
                         ->where('office_id','=',Auth::user()->office_id)
                         ->pluck('id');
        }

        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        $text      = '/LOST/'.$from.'/'.$to;
        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;

        $enquiry_list   = Enquiry::where('user_id','=',Auth::user()->id)
                    ->where('office_id','=',Auth::user()->office_id)
                    ->whereBetween('enq_date', [$from, $to])
                    ->orderBy('created_at','desc')
                    ->get();
        
        return view('teamenquiry/lostindex',compact('status','status_id','enquiry','from','to','text','role','enquiry_list'));
    }
    
    
}