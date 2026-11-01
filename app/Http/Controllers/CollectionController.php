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
use App\Quotation;
use App\FunctionType;
use App\InfluencerType;
use Auth;
use App\UserRole;
use App\Places;
use App\BusinessAssociate;
use App\Freebie;
use App\EmployeeDetail;
use App\UnitMeasurement;
use Carbon\Carbon;
use App\CustomerType;
use App\TallyBillOutstanding;
use App\CollectionFollowupLog;


class CollectionController extends Controller
{
    public function followupShow(Request $request){
        $ft = $request->followup_type ?? 'COMMITED_DATE';
        $cmtd_dt = $request->commited_date ?? date('d-m-Y');
        $cmtd_dt = Carbon::createFromFormat('d-m-Y', $cmtd_dt)->format('Y-m-d');
        $date = date('Y-m-d');
        $tally_bills = TallyBillOutstanding::whereColumn('alias', 'customers.id')
            ->where('receivable', '>', 0)
            ->join('customers', 'tally_bill_outstanding.alias', '=', 'customers.id')
            ->leftJoin('collection_followup_logs', function($join) use ($cmtd_dt) {
                $join->on('collection_followup_logs.customer_id', '=', 'customers.id')
                     ->where('collection_followup_logs.id', function($query) use ($cmtd_dt) {
                         $query->selectRaw('MAX(id)')
                               ->from('collection_followup_logs')
                               ->whereColumn('customer_id', 'customers.id')//;
                               ->whereDate('next_commit_date', $cmtd_dt);
                     });
            });
        if($ft != ''){
            $tally_bills = $tally_bills->where('customers.followup_type', $ft);
        }
            $tally_bills = $tally_bills->selectRaw('customers.id, customers.customer_name, customers.customer_phone, collection_followup_logs.next_commit_date, sum(tally_bill_outstanding.receivable) as total_outstanding, min(tally_bill_outstanding.bill_due_date) as max_due_date')
            ->groupBy('customers.id', 'customers.customer_name', 'customers.customer_phone','collection_followup_logs.next_commit_date');
            // ->orderByDesc('max_due_date')
        if($cmtd_dt != date('Y-m-d')){
            $tally_bills = $tally_bills->orderBy('next_commit_date');
        }else{    
            $tally_bills = $tally_bills->orderBy('max_due_date');
        }
        $tally_bills = $tally_bills->get();
        $days[] = ['id' => 0, 'day_val' => '30 Days'];
         $days[] = ['id' => 1, 'day_val' => '60 Days'];
         $days[] = ['id' => 2, 'day_val' => '90 Days'];
         $days[] = ['id' => 3, 'day_val' => '120 Days'];
         $days[] = ['id' => 4, 'day_val' => 'Above 120 Days'];
         $day_id = -1;
         $from = date('1-m-Y');
         $to = date('t-m-Y');
         $followup_types = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY', 'COMMITED_DATE'];
         $colloection_followup_log = new CollectionFollowupLog();
        return view('collection.followup_index', compact('tally_bills', 'days', 'day_id', 'from', 'to', 'followup_types','ft','colloection_followup_log','cmtd_dt'));
    }

    public function editFollowup($id, $from, $to, $folotype = null){
        $customer = Customer::find($id);
        $tally_bills = TallyBillOutstanding::where('alias', $id)
            ->where('receivable', '>', 0)
            ->join('customers', 'tally_bill_outstanding.alias', '=', 'customers.id')
            ->leftJoin('places', 'customers.place_id', '=', 'places.id')
            ->selectRaw('customers.id, customers.customer_name, customers.customer_phone, places.place, tally_bill_outstanding.bill_no, tally_bill_outstanding.bill_date, tally_bill_outstanding.bill_due_date, tally_bill_outstanding.receivable,tally_bill_outstanding.bill_amount,tally_bill_outstanding.id as bill_id')
            ->orderBy('tally_bill_outstanding.bill_due_date')
            ->get();
        $followup_logs = CollectionFollowupLog::where('customer_id', $id)
            // ->whereBetween('followup_date', [Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d'), Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d')])
            ->orderByDesc('followup_date')
            ->orderByDesc('followup_time')
            ->get();
        $next_commit_day  = Carbon::today()->addDays(7);       
        switch ($customer->followup_type) {
            case 'MONDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::MONDAY);
                break;
            case 'TUESDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::TUESDAY);
                break; 
            case 'WEDNESDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::WEDNESDAY);
                break;
            case 'THURSDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::THURSDAY);
                break; 
            case 'FRIDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::FRIDAY);
                break;
            case 'SATURDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::SATURDAY);
                break;
            case 'SUNDAY':
                $next_commit_day  = Carbon::today()->next(Carbon::SUNDAY);
                break;            
            default:
                $next_commit_day  = Carbon::today()->addDays(7);
                break;
        };   
        return view('collection.edit_followup', compact('customer', 'from', 'to', 'tally_bills', 'followup_logs','next_commit_day','folotype'));
    }

    public function saveBillDetails(Request $request){
        $bill_no = $request->bill_no;
        $bill_date = Carbon::createFromFormat('d-m-Y', $request->bill_date)->format('Y-m-d');
        $bill_amount = $request->bill_amount;
        $outstanding = $request->outstanding;
        $cust_id = $request->cust_id;

        $tally_bill = new TallyBillOutstanding();
        $tally_bill->party_name = Customer::find($cust_id)->customer_name;
        $tally_bill->alias = $cust_id;
        $tally_bill->bill_no = $bill_no;
        $tally_bill->bill_date = $bill_date;
        $tally_bill->bill_amount = $bill_amount;
        $tally_bill->receivable = $outstanding;
        $due_date = Carbon::createFromFormat('Y-m-d', $bill_date)->addDays(30);
        $tally_bill->bill_due_date = $due_date->format('Y-m-d');
        $tally_bill->save();

        return response()->json(['id' => $tally_bill->id, 'due_date' => $due_date->format('d-m-Y'), 'due_days' => now()->diffInDays($due_date)]);
    }

    public function deleteBillDetails(Request $request){
        $id = $request->id;
        TallyBillOutstanding::destroy($id);
        return response()->json(['status' => 'success']);
    }

    public function updateFollowup(Request $request){
        $customer_id = $request->customer_id;
        $followup_date = date('Y-m-d');
        $followup_time = date('H:i:s');
        $next_commit_date = Carbon::createFromFormat('d-m-Y', $request->commited_date)->format('Y-m-d');
        $remarks = $request->followup_remark;

        $followup_log = new CollectionFollowupLog();
        $followup_log->followup_date = $followup_date;
        $followup_log->followup_time = $followup_time;
        $followup_log->next_commit_date = $next_commit_date;
        $followup_log->remarks = $remarks;
        $followup_log->user_id = Auth::user()->id;
        $followup_log->office_id = Auth::user()->office_id;
        $followup_log->employee_id = EmployeeDetail::where('id', Auth::user()->employee_id)->first()->id;
        $followup_log->customer_id = $request->customer_id;
        $followup_log->save();

        $customer = Customer::find($customer_id);
        $customer->followup_type = $request->followup_type;
        $customer->is_critical = $request->is_critical ? 1 : 0;
        $customer->save();
        
        $today = Carbon::now()->format('Y-m-d');
        $today_list  = CollectionFollowupLog::whereDate('followup_date', '=', $today)
                            ->pluck('customer_id')
                            ->toArray();
        $folotype = $request->folotype;                    
        if($folotype == 'COMMITED_DATE'){                
            $nextCommittedCustomer = Customer::whereHas('collectionFollowups', function($q) use ($today) {
                $q->whereDate('next_commit_date', '=', $today)
                ->orderBy('next_commit_date');
            })->where('id', '!=', $customer_id)
            ->whereNotIn('id', $today_list)
            ->orderBy('id')
            ->first();
        } else {
            $nextCommittedCustomer = Customer::where('followup_type', '=', $folotype)
            ->where('id', '!=', $customer_id)
            ->whereNotIn('id', $today_list)
            ->orderBy('id')
            ->first();
        }
        if ($nextCommittedCustomer) {
            $from = $request->from;
            $to   = $request->to;
            $folotype = $request->folotype;
            return redirect()->route(
                'collection.followup.edit',
                [$nextCommittedCustomer->id, $from, $to, $folotype]
            )->with('success', 'Follow-up updated. Next customer loaded.');
        }
        $nextCustomer = TallyBillOutstanding::where('receivable', '>', 0)
        ->where('alias', '!=', $customer_id)
        ->whereNotIn('alias', $today_list)
        ->orderBy('bill_due_date', 'asc')
        ->select('alias')
        ->first();
        $from = $request->from;
        $to   = $request->to;
        $folotype = $request->folotype;
        if ($nextCustomer) {
            return redirect()->route(
                    'collection.followup.edit',
                    [$nextCustomer->alias, $from, $to, $folotype]
                )->with('success', 'Follow-up updated. Next customer loaded.');
        }

        return redirect()->route('collection.followups.index')->with('success', 'Followup updated successfully.');
    }

    public function collectionLog(Request $request)
    {
        $enq_id   = $request->get('id');
        $enq_log  = CollectionFollowupLog::join('customers','customers.id','=','collection_followup_logs.customer_id')->join('users','users.id','=','collection_followup_logs.user_id')
        ->join('employee_details','employee_details.id','=','collection_followup_logs.employee_id')
                              ->where('collection_followup_logs.customer_id','=',$enq_id)
                              ->orderBy('collection_followup_logs.created_at','desc')
                              ->selectRaw('collection_followup_logs.*,customers.customer_name,users.username,employee_details.name as employee_name')
                              ->limit(10)
                              ->get();
        return $enq_log;
    }    
}
