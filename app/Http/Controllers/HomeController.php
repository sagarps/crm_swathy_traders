<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\EmployeeDetail;
use App\Customer;
use App\EnquiryStatus;
use App\Enquiry;
use App\TeamMapping;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $emp_count        = null;
        $cust_count       = null;
        $sale_close_count = null;
        $enq_count        = null;

        $enq_today_count  = null;
        $enq_month_count  = null;
        $enq_total_count  = null;

        $sc_today_count   = null;
        $sc_month_count   = null;
        $sc_total_count   = null;

        $flw_today_count  = null;
        $flw_month_count  = null;
        $flw_total_count  = null;

        $sale_close_stat  = EnquiryStatus::where('status_name','=','SALE CLOSE')
                                         ->first()->id;
        $follow_up_stat   = EnquiryStatus::where('status_name','=','FOLLOW UP')
                                         ->first()->id;
//////////admin/////////
    if(Auth::user()->userpermission(2) || Auth::user()->userpermission(3) || Auth::user()->userpermission(4) || Auth::user()->userpermission(5) || Auth::user()->userpermission(7) || Auth::user()->userpermission(8) || Auth::user()->userpermission(9) || Auth::user()->userpermission(10) || Auth::user()->userpermission(14)){
        $emp_count        = EmployeeDetail::where('office_id','=',Auth::user()->office_id)
                                          ->count();
        $cust_count       = Customer::where('office_id','=',Auth::user()->office_id)
                                          ->count();
        $sale_close_count = Enquiry::where('enquiry_status_id','=',$sale_close_stat)
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->count();
        $enq_count        = Enquiry::where('office_id','=',Auth::user()->office_id)
                                   ->count();



        $user = User::find(Auth::user()->id);
    }

////////////////////////
if(Auth::user()->userpermission(12)) {
    $enq_today_count  =  Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->where('enq_date','=',date('Y-m-d'))
                                    ->count();
    $enq_total_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->count();

    $enq_month_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->whereDate('enq_date','>=',date('Y-m-1'))
                                    ->whereDate('enq_date','<=',date('Y-m-t'))
                                    ->count();
    $sc_today_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
                                ->count();

    $sc_month_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
                                ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
                                ->count();
    $sc_total_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->count();
    $cnv_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
                                ->count();
    $cnv_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
                                ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
                                ->count();
    $cnv_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->count();
    $flw_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->where('enquiry_logs.status_date','=',date('Y-m-d'))
                                ->count();
    $flw_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.active','=','Y')
                                ->whereBetween('enquiry_logs.status_date',[date('Y-m-1'),date('Y-m-t')])
                                ->count();
     $flw_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                ->where('enquiry_logs.status_date','<',date('Y-m-d'))
                                ->where('enquiry_logs.active','=','Y')
                                ->count();

}
else {
    $enq_today_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->where('user_id','=',Auth::user()->id)
                                    ->where('enq_date','=',date('Y-m-d'))
                                    ->count();
    $enq_total_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->where('user_id',Auth::user()->id)
                                    ->count();
    $enq_month_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
                                    ->where('user_id','=',Auth::user()->id)
                                    ->whereDate('enq_date','>=',date('Y-m-1'))
                                    ->whereDate('enq_date','<=',date('Y-m-t'))
                                    ->count();
    $sc_today_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
                                    ->count();

    $sc_month_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
                                    ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
                                    ->count();
    $sc_total_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->count();
    $cnv_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
                                    ->count();
    $cnv_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
                                    ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
                                    ->count();
    $cnv_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->count();
    $flw_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->where('enquiry_logs.status_date','=',date('Y-m-d'))
                                    ->count();
    $flw_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->whereBetween('enquiry_logs.status_date',[date('Y-m-1'),date('Y-m-t')])
                                    ->count();
    $flw_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
                                    ->where('enquiry.user_id','=',Auth::user()->id)
                                    ->where('enquiry_logs.status_date','<',date('Y-m-d'))
                                    ->where('enquiry_logs.active','=','Y')
                                    ->count();


}
// //////////executive/////////
//     if(Auth::user()->userpermission(11)  || Auth::user()->userpermission(13)){
//         $emp_ids   = TeamMapping::where('parent_id','=',Auth::user()->employee_id)
//                                 ->where('office_id','=',Auth::user()->office_id)
//                                 ->pluck('employee_id');
//         $length    = count($emp_ids);
//         $emp_ids->offsetSet($length,Auth::user()->employee_id);

//         $users     = null;

//         if($emp_ids != null){
//             $users = User::whereIn('employee_id',$emp_ids)
//                          ->where('office_id','=',Auth::user()->office_id)
//                          ->pluck('id');
//         }

//         $enq_today_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
//                                     ->whereIn('user_id',$users)
//                                     ->where('enq_date','=',date('Y-m-d'))
//                                     ->count();
//         $enq_total_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
//                                     ->whereIn('user_id',$users)
//                                     ->count();
//         $enq_month_count  = Enquiry::where('office_id','=',Auth::user()->office_id)
//                                    ->whereIn('user_id',$users)
//                                    ->whereDate('enq_date','>=',date('Y-m-1'))
//                                    ->whereDate('enq_date','<=',date('Y-m-t'))
//                                    ->count();



//         $sc_today_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
//                                    ->count();
//         $sc_month_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
//                                    ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
//                                    ->count();
//         $sc_total_count   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$sale_close_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->count();

//         $cnv_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->whereDate('enquiry_logs.created_at','=',date('Y-m-d'))
//                                    ->count();
//         $cnv_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->whereDate('enquiry_logs.created_at','>=',date('Y-m-1'))
//                                    ->whereDate('enquiry_logs.created_at','<=',date('Y-m-t'))
//                                    ->count();
//         $cnv_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->count();

//         $flw_today_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->where('enquiry_logs.status_date','=',date('Y-m-d'))
//                                    ->count();
//         $flw_month_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->whereBetween('enquiry_logs.status_date',[date('Y-m-1'),date('Y-m-t')])
//                                    ->count();
//         $flw_total_count  = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
//                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
//                                    ->where('enquiry_logs.enquiry_status_id','=',$follow_up_stat)
//                                    ->whereIn('enquiry_logs.user_id',$users)
//                                    ->where('enquiry_logs.status_date','<',date('Y-m-d'))
//                                    ->where('enquiry_logs.active','=','Y')
//                                    ->count();
//         }
////////////////////////////
           $topten_accociate_lead=Enquiry::where('office_id','=',Auth::user()->office_id)
           ->whereNotNull('associate_id')
           ->selectRaw('associate_id,count(enq_no) as totlead')
           ->groupBy('associate_id')->orderBy('totlead','Desc')->get()->take(10);
          //return $topten_accociate_lead;

         // return $place_lead;

        return view('home',compact('user','emp_count','cust_count','sale_close_count','enq_count','enq_today_count','enq_month_count','enq_month_count','enq_total_count','sc_today_count','sc_month_count','sc_total_count','flw_today_count','flw_month_count','flw_total_count','cnv_today_count','cnv_month_count','cnv_total_count','topten_accociate_lead'));
    }
}
