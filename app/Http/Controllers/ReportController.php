<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enquiry;
use App\EnquiryStatus;
use App\EnquiryLog;
use App\EmployeeDetail;
use App\ComplaintType;
use App\ComplaintStatus;
use App\User;
use App\Complaint;
use Auth;
use App\Freebie;
use App\FreebieIssue;
use App\BusinessAssociate;
use App\CollectionFollowupLog;


class ReportController extends Controller
{

    public function enqIndex(Request $request)
    {
        $status   = EnquiryStatus::where('is_active','=','Y')
        						 ->get();
       	$employee = EmployeeDetail::where('office_id','=',Auth::user()->office_id)
                                    ->where('is_active','=','Y')
       							  ->get();

    	return view('reports/enquiry_report_index',compact('status','employee'));
    }

    public function enqShow(Request $request)
    {
        $from      = date('Y-m-d',strtotime($request->get('from')));
    	$to        = date('Y-m-d',strtotime($request->get('to')));
    	$status_id = $request->get('status');
    	$emp_id    = $request->get('employee');
    	$status    = null;
    	$employe   = null;
    	$date      = new EnquiryLog;

    	$enquiry = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                    ->where('enquiry.office_id','=',Auth::User()->office_id)
                    ->where('enquiry_logs.active','=','Y');

    	if($status_id != null){
    		$status  = EnquiryStatus::find($status_id);
			$enquiry = $enquiry->where('enquiry.enquiry_status_id','=',$status_id);
   		}

   		if($emp_id != null){
            	$enquiry = $enquiry->where('enquiry.staff_id','=',$emp_id);

   			// $employe = EmployeeDetail::find($emp_id);
    		// $user = User::where('employee_id','=',$emp_id)
    		// 			   ->first();
    		// if($user != null){
			// 	$enquiry = $enquiry->where('enquiry.staff_id','=',$user['id']);
    		// }
   		}
    	if($request->get('status_date') == 'Y') {
                $enquiry = $enquiry->whereBetween('enquiry_logs.status_date',[$from,$to]);
        } else {
                $enquiry = $enquiry->whereBetween('enq_date',[$from,$to]);

        }

        $enquiry = $enquiry->select('enquiry.*','enquiry_logs.status_remaks','enquiry_logs.status_date')
	    				   ->get();
        $product_raw='N';
        if($request->get('show_products') == 'Y') {
            $enquiry = $enquiry->load('enquiryItem');
            $product_raw='Y';
        }

    	return view('reports/enquiry_report_show',compact('enquiry','from','to','status','date','employe','product_raw'));
    }

    public function cmpIndex(Request $request)
    {
        $type   = ComplaintType::where('is_active','=','Y')
                               ->get();
        $status = ComplaintStatus::where('is_active','=','Y')
                                 ->get();

        return view('reports/complaint_report_index',compact('status','type'));
    }

    public function cmpShow(Request $request)
    {
        $from      = date('Y-m-d',strtotime($request->get('from')));
        $to        = date('Y-m-d',strtotime($request->get('to')));
        $status_id = $request->get('status');
        $type_id   = $request->get('type');
        $status    = null;
        $type      = null;
        $date      = new EnquiryLog;

        $complaint = Complaint::where('office_id','=',Auth::User()->office_id);

        if($status_id != null){
            $status  = ComplaintStatus::find($status_id);
            $complaint = $complaint->where('complaint_status_id','=',$status_id);
        }

        if($type_id != null){
            $type      = ComplaintType::find($type_id);
            $complaint = $complaint->where('complaint_type_id','=',$type_id);
        }

        $complaint = $complaint->whereBetween('complaint_date',[$from,$to])
                               ->get();

        return view('reports/complaint_report_show',compact('complaint','from','to','status','date','type'));
    }

    public function getFreebieReport(Request $request)
    {
        $freebie   = Freebie::all();
    	return view('reports/freebie_report_index',compact('freebie'));
    }

    public function showFreebieReport(Request $request)
    {
        $from      = date('Y-m-d',strtotime($request->get('from')));
    	$to        = date('Y-m-d',strtotime($request->get('to')));
    	$freebie_id = $request->get('freebie');

    	$freebies = FreebieIssue::whereBetween('freebie_issue_date',[$from,$to]);
        if($freebie_id) {
            $freebies = $freebies->where('freebie_type_id','=',$freebie_id);
        }
        $freebies = $freebies->get();
        // dd($freebies);
        $freebie_type  = Freebie::find($freebie_id);

    	return view('reports/freebie_report_show',compact('freebies','from','to','freebie_type'));
    }
    public function perIndex(Request $request)
    {


    	return view('reports/performance_report');
    }

    public function perShow(Request $request)
    {
      //  return $request;

        $from      = date('Y-m-d',strtotime($request->get('from')));
    	$to        = date('Y-m-d',strtotime($request->get('to')));
        $enquiry= new Enquiry();

        if($request->type=='A'){


            $datas = BusinessAssociate::all();
           // return $datas;
            return view('reports/performance_show',compact('from','to','enquiry','datas'));
        }
        else{

            $datas = Enquiry::whereBetween('enq_date',[$from,$to])
            ->selectRaw('staff_id,count(enq_no) as totlead')
            ->groupBy('staff_id')->get();
            return view('reports/staff_report_show',compact('from','to','datas'));

        }


    }

    public function actIndex(Request $request)
    {
        $employee = EmployeeDetail::where('office_id','=',Auth::user()->office_id)
                                    ->where('is_active','=','Y')
                                    ->get();

        return view('reports/activity_report_index',compact('employee'));
    }

    public function actShow(Request $request)
    {
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $employee = EmployeeDetail::find($request->employee);
        $activity = EnquiryLog::whereBetween('created_at',[$from,$to]);
        if($request->employee != null){
            $user_id = User::where('employee_id','=',$request->employee)
                         ->first()->id ?? null;
            $activity = $activity->where('user_id','=',$user_id); 
        }
        $activity = $activity->orderBy('created_at')->get();
        
        return view('reports/activity_report_show',compact('from','to','activity','employee'));
    }

    public function followupReport(Request $request)
    {
        $employee = EmployeeDetail::where('office_id','=',Auth::user()->office_id)
                                    ->where('is_active','=','Y')
                                    ->get();
        return view('reports/collection_followup_report_index',compact('employee'));
    }

    public function followupReportShow(Request $request)
    {
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $employee = EmployeeDetail::find($request->employee);
        $followups = CollectionFollowupLog::whereBetween('followup_date',[$from,$to]);
        if($request->employee != null){
            // $user_id = User::where('employee_id','=',$request->employee)
            //              ->first()->id ?? null;
            $followups = $followups->where('employee_id','=',$request->employee); 
        }
        $followups = $followups->orderBy('followup_date')->orderBy('followup_time')->get();
        
        return view('reports/collection_followup_report_show',compact('from','to','followups','employee'));
    }
}
