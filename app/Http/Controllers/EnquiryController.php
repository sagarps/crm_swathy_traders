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


class EnquiryController extends Controller
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
        
        $role = Auth::user()->userRole->role->name;
                    
        $enquiry = null;
        if(Auth::user()->userpermission(12)) {
            $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        
        }
        else {
            $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                                // ->where('user_id','=',Auth::user()->id)
                                ->where('staff_id','=',Auth::user()->employee_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->whereBetween('enq_date', [$from, $to])
                                ->orderBy('created_at','desc')
                                ->get();
            
        }

        // dd($enq->enq_no);
        $text      = '/NEWLEAD/'.$from.'/'.$to;
        // die($enquiry->customer);
        return view('enquiry/index',compact('status','status_id','enquiry','from','to','text'));
    }

    public function newLead(Request $request,$from,$to)
    {
        // dd("sdad");
        $status    = EnquiryStatus::where('is_active','=','Y')
                                  ->orderBy('seq')
                                  ->get();
        $status_id = $status->where('status_name','=','NEW LEAD')
                            ->first()->id;
        $role = Auth::user()->userRole->role->name;
        if(Auth::user()->userpermission(12)) {

        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        }
        else {
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                        ->where('office_id','=',Auth::user()->office_id)
                        // ->where('user_id','=',Auth::user()->id)
                        ->where('staff_id','=',Auth::user()->employee_id)
                        ->whereBetween('enq_date', [$from, $to])
                        ->orderBy('created_at','desc')
                        ->get();
        }
         

        $text      = '/NEWLEAD/'.$from.'/'.$to;
        // dd("jii");
        return view('enquiry/index',compact('status','status_id','enquiry','from','to','text'));
    }

    public function followUP(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','FOLLOW UP')
                            ->first()->id;

        if(Auth::user()->userpermission(12)) {
        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereBetween('enquiry_logs.status_date', [$from, $to])
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get(); 
        }
        else {
        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                                ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                                ->where('enquiry_logs.user_id','=',Auth::user()->id)
                                ->where('enquiry.office_id','=',Auth::user()->office_id)
                                ->whereBetween('enquiry_logs.status_date', [$from, $to])
                                ->where('enquiry_logs.active','=','Y')
                                ->orderBy('enquiry_logs.status_date','desc')
                                ->selectRaw('enquiry.*,enquiry_logs.status_date')
                                ->get(); 
         }

        // dd($from."---".$to);

        $text      = '/FOLLOWUP/'.$from.'/'.$to;
     
        return view('enquiry/followupindex',compact('status','status_id','enquiry','from','to','text'));
    }

    public function saleClose(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','SALE CLOSE')
                            ->first()->id;

        if(Auth::user()->userpermission(12)) {

        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereDate('enquiry_logs.created_at','>=',$from)
                            ->whereDate('enquiry_logs.created_at','<=',$to)
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get();
        }
        else {
            $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry_logs.user_id','=',Auth::user()->id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereDate('enquiry_logs.created_at','>=',$from)
                            ->whereDate('enquiry_logs.created_at','<=',$to)
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date,enquiry_logs.enquiry_remaks')
                            ->get();
        }

        $text      = '/SALECLOSE/'.$from.'/'.$to;
        $user_role = UserRole::where('user_id',Auth::user()->id)->first();
        $role = $user_role->role_id;
        // dd("in ");

        return view('enquiry/salecloseindex',compact('status','status_id','enquiry','from','to','text','role'));
    }

    public function cancel(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','CANCEL')
                            ->first()->id;
        if(Auth::user()->userpermission(12)) {

        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        }
        else {
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                        ->where('office_id','=',Auth::user()->office_id)
                        // ->where('user_id','=',Auth::user()->id)
                        ->where('staff_id','=',Auth::user()->employee_id)
                        ->whereBetween('enq_date', [$from, $to])
                        ->orderBy('created_at','desc')
                        ->get();
        }

        $text      = '/CANCEL/'.$from.'/'.$to;
        return view('enquiry/cancelindex',compact('status','status_id','enquiry','from','to','text'));
    }

    public function lost(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','LOST')
                            ->first()->id;
        if(Auth::user()->userpermission(12)) {
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        }
        else {
            $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
            ->where('office_id','=',Auth::user()->office_id)
            // ->where('user_id','=',Auth::user()->id)
            ->where('staff_id','=',Auth::user()->employee_id)
            ->whereBetween('enq_date', [$from, $to])
            ->orderBy('created_at','desc')
            ->get();

        }

        $text      = '/LOST/'.$from.'/'.$to;
        return view('enquiry/lostindex',compact('status','status_id','enquiry','from','to','text'));
    }

    

    public function create($from,$to)
    {   
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->where('is_active','=','Y')
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                    ->get();
        $status       = EnquiryStatus::where('is_active','=','Y')
                                     ->orderBy('seq')
                                     ->get();
        $func_type    = FunctionType::all();
        $inf_type     = InfluencerType::all();
        $places = Places::all();
        $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
                                    ->where('designations.designation','SALES EXECUTIVE')
                                    ->select('employee_details.id','employee_details.name')
                                    ->get();
        $enq = Enquiry::orderBy('enq_no', 'desc')->first();
        if($enq) {
            $enq_no = $enq->enq_no;
        }
        else {
            $enq_no = 00;
        }
        $role = Auth::user()->userRole->role->name;
        $staff_id = NULL;
        if(!(Auth::user()->userpermission(12))) {
            $staff_id = Auth::user()->employee->id;
        }

        $customer_types = CustomerType::all();

        return view('enquiry/create',compact('employee','type','lead_type','status','from','to','func_type','inf_type','enq_no','places','staff_id','customer_types'));
    }

    public function store(Request $request)
    {  
        $this->validate($request,[
            'func_date'=>'required|date|after:yesterday'
        ],
         [
            'func_date.after' => 'Function date is not valid',
        ]
        
        );
        
        $statusdate = date('Y-m-d');
        $from       = $request->get('from');
        $to         = $request->get('to');
        $stat       = EnquiryStatus::find($request->get('status'))->status_name;

        if($request->get('flwdate') != null && $stat == 'FOLLOW UP'){
            $statusdate = date('Y-m-d',strtotime($request->get('flwdate')));
        }
        
        if($request->get('deliverydate') != null && $stat == 'SALE CLOSE'){
            $statusdate = date('Y-m-d',strtotime($request->get('deliverydate')));
        }
       
        if($request->get('search') == null){
            $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                              ->where('office_id','=',Auth::user()->office_id)
                              ->count();
            if($check2 > 0){
                return redirect()->route('enquiry.index')->with('number','true');
            }
            $customer = Customer::firstorcreate([
                                                    'customer_name'  => strtoupper($request->get('name')),
                                                    'customer_phone' => $request->get('phone'),
                                                    'office_id'      => Auth::user()->office_id,
                                               ]);
            $customer->place_id = $request->get('place');
            $customer->alternate_phone = $request->get('alt_phone');
            if ($request->has('customer_type_id')) {
                $customer->customer_type_id = $request->get('customer_type_id');
            }
            $customer->address = $request->get('address');
            $customer->save();
            $customer_id = $customer->id;
            $exist       = 0;
        }
        else{
            if ( strpos( $request->get('search'), '/' ) !== false ) {
                $info     = explode( '/', $request->get('search') );
                $customer = Customer::where('customer_name','=',$info[0])
                                    ->where('customer_phone','=',$info[1])
                                    ->where('office_id','=',Auth::user()->office_id)
                                    ->first();
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                  ->where('id','!=',$customer->id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('enquiry.index')->with('number','true');
                }
                if($customer != null){    
                    $customer->customer_name  = strtoupper($request->get('name'));              
                    $customer->customer_phone = $request->get('phone');              
                    $customer->office_id      = Auth::user()->office_id;
                    $customer->place_id = $request->get('place'); 
                    $customer->alternate_phone = $request->get('alt_phone');
                    if ($request->has('customer_type_id')) {
                        $customer->customer_type_id = $request->get('customer_type_id');
                    }
                    $customer->address         = $request->get('address');                             
                    $customer->save();   
                }
                else{
                    $customer = Customer::firstorcreate([
                                                            'customer_name'  => strtoupper($request->get('name')),
                                                            'customer_phone' => $request->get('phone'),
                                                            'office_id'      => Auth::user()->office_id,
                                                            'alternate_phone' => $request->get('alt_phone'),
                                                            'place_id'          => $request->get('place'),
                                                            'customer_type_id' => $request->get('customer_type_id'),
                                                            'address'          => $request->get('address'),
                                                       ]);
                }
            }
            else{
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('enquiry.index')->with('number','true');
                }
                $customer = Customer::firstorcreate([
                                                        'customer_name'  => strtoupper($request->get('name')),
                                                        'customer_phone' => $request->get('phone'),
                                                        'office_id'      => Auth::user()->office_id,
                                                  ]);
                $customer->place_id = $request->get('place');
                $customer->alternate_phone = $request->get('alt_phone');
                if ($request->has('customer_type_id')) {
                    $customer->customer_type_id = $request->get('customer_type_id');
                }
                $customer->address = $request->get('address');
                $customer->save();
            }
            $customer_id = $customer->id;
            $exist       = 1;
        }

        $no = Enquiry::count();

        if($no == 0){
            $num = '00001';
        }
        else{
            $num = $no+1; 
        }
        $num     = str_pad($num, 5, "0", STR_PAD_LEFT);
        $func_date = date('Y-m-d',strtotime($request->get('func_date')));
        $purchase_date = date('Y-m-d',strtotime($request->get('purchase_date')));
        //'gender'            => $request->get('gen'),
        $enquiry = Enquiry::create([
                                    'enq_date'          => date('Y-m-d'),
                                    'enq_no'            => $num,
                                    'enquiry_status_id' => $request->get('status'),
                                    'new_exist'         => $exist,
                                    'customer_id'       => $customer_id,
                                    'user_id'           => Auth::user()->id,
                                    'office_id'         => Auth::user()->office_id,
                                    'enq_type'          => $request->get('enqtype'),
                                    'lead_type_id'      => $request->get('lead'),
                                    'function_date'     => $func_date,
                                    'function_type'     => $request->get('func_type'),
                                    'influencer_type'   => $request->get('inf_type'),
                                    'associate_id'     => $request->get('inf_name'),
                                    'address'           => $request->get('address'),
                                    'reference_name'    => $request->get('ref_name'),
                                    'reference_phone'   => $request->get('ref_phone'),
                                    'staff_id'          => $request->has('staff_name') ? $request->get('staff_name') : Auth::user()->employee_id,
                                    'purchase_date'     => $purchase_date,
                                    'purchase_value'    => $request->get('purchase_value'),
                                    'bill_no'           => $request->get('bill_no'),
                                    'coupon_no'           => $request->get('coupon_no'),
                                  ]);
        EnquiryLog::where('enquiry_id','=',$enquiry->id)
                  ->update(['active' => 'N']);
        EnquiryLog::create([
                            'enquiry_id'        => $enquiry->id,
                            'enquiry_status_id' => $request->get('status'),
                            'status_date'       => $statusdate,
                            'status_remaks'     => strtoupper($request->get('remarks')),
                            'user_id'           => Auth::user()->id,
                            'active'            => 'Y',
                            'office_id'         => Auth::user()->office_id,
                         ]);
        
        return redirect()->route('enquiry.index')->with('save','true');
    }

    public function customerList(Request $request)
    { 
        $item = Customer::where('office_id','=',Auth::user()->office_id)
                        ->where('customer_name','like','%'.$request->get('term').'%')
                        ->orWhere('customer_phone','like','%'.$request->get('term').'%')
                        ->orWhere('alternate_phone','like','%'.$request->get('term').'%')
                        ->selectRaw('customer_name,customer_phone,id,alternate_phone')->orderBy('customer_name')
                        ->get();

        return $item;
    }

    public function customerShow(Request $request)
    {
        if( strpos( $request->get('value'), '/' ) !== false ){
            $info     = explode( '/', $request->get('value') );
            $customer = Customer::where('customer_name','=',$info[0])
                                ->where('customer_phone','=',$info[1])
                                ->where('office_id','=',Auth::user()->office_id);
            if(isset($info[2])) {
                $customer = $customer->where('alternate_phone','=',$info[2]);
            }
            $customer = $customer->first();

            return $customer;
        }
    }

    public function edit($id,$from,$to)
    {   

        $enquiry      = Enquiry::find($id);

        $place_id = Customer::where('id',$enquiry->customer_id)->first()->place_id;
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->where('is_active','=','Y')
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
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->orderBy('created_at','desc')
                                  ->get();
        $enq_date     = EnquiryLog::join('enquiry_status','enquiry_status.id','=','enquiry_logs.enquiry_status_id')
                                  ->where('enquiry_logs.enquiry_id','=',$enquiry->id)
                                  ->where('enquiry_logs.active','=','Y')
                                  ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
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
        $text      = $text.'/'.$from.'/'.$to;

        /*product details*/
        $category  = ProductCategory::orderBy('category_name')->get();
        $products = Product::where('is_active','Y')
                            ->orderBy('name')
                            ->get();
        $units = UnitMeasurement::all();
        /*$enqpro    = EnquiryItem::where('enquiry_id','=',$enquiry->id)
                                ->get();*/
        $enqpro    = EnquiryItem::join('products','products.id','=','enquiry_items.product_id')
                                ->join('uom','uom.id','=','enquiry_items.unit_id')
                                ->where('enquiry_items.enquiry_id','=',$enquiry->id)
                                ->selectRaw('enquiry_items.*,products.name,uom.unit')
                                ->orderBy('products.name')
                                ->get();

        $quotation = Quotation::where('enquiry_id','=',$id) 
                              ->where('office_id','=',Auth::User()->office_id)
                              ->orderBy('created_at','desc')
                              ->get();
        $places  = Places::all();
        $associates = BusinessAssociate::where('associate_type_id',$enquiry->influencer_type)->get();
        $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
                        ->where('designations.designation','SALES EXECUTIVE')
                        ->select('employee_details.id','employee_details.name')
                        ->get();  
        $customer_types = CustomerType::all();
        return view('enquiry/edit',compact('place_id','employee','type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','text','from','to','category','enqpro','quotation','remarks','func_type','inf_type','places','associates','products','units','customer_types'));
    }

    public function update(Request $request)
    {   

        
        $this->validate($request,[
            'func_date'=>'required|date|after:yesterday'
        ],
         [
            'func_date.after' => 'Function date is not valid',
        ]
        
        );
        $from       = $request->get('from');
        $to         = $request->get('to');
        $statusdate = date('Y-m-d');
        $enquiry    = Enquiry::find($request->get('edit_id'));
        $stat       = EnquiryStatus::find($request->get('status'))->status_name;
        if($request->get('flwdate') != null && $stat == 'FOLLOW UP'){
            $statusdate = date('Y-m-d',strtotime($request->get('flwdate')));
        }

        if($request->get('deliverydate') != null && $stat == 'SALE CLOSE'){
            $statusdate = date('Y-m-d',strtotime($request->get('deliverydate')));
        }
        if($request->get('search') == null){
            $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                              ->where('id','!=',$enquiry->customer_id) //exception takinf customer id directly from enquiry
                              ->where('office_id','=',Auth::user()->office_id)
                              ->count();
                            //   dd($check2->customer_name);

            if($check2 > 0){

                return redirect()->route('enquiry.index')->with('number','true');
            }
            $customer = Customer::firstorcreate([
                                                    'customer_name'  => strtoupper($request->get('name')),
                                                    'customer_phone' => $request->get('phone'),
                                                    'office_id'      => Auth::user()->office_id,
                                               ]);
            $customer->place_id = $request->get('place');
            $customer->alternate_phone = $request->get('alt_phone');
            if ($request->has('customer_type_id')) {
                $customer->customer_type_id = $request->get('customer_type_id');
            }
            $customer->address         = $request->get('address');
            $customer->save();

            $customer_id = $customer->id;
            $exist       = 0;
        }
        else{
            
            if ( strpos( $request->get('search'), '/' ) !== false ) {
                $info     = explode( '/', $request->get('search') );
                $customer = Customer::where('customer_name','=',$info[0])
                                    ->where('customer_phone','=',$info[1]);
              
                    $customer = $customer->where('office_id','=',Auth::user()->office_id)
                                    ->first();
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                  ->where('id','!=',$customer->id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('enquiry.index')->with('number','true');
                }
                if($customer != null){    
                    $customer->customer_name  = strtoupper($request->get('name'));              
                    $customer->customer_phone = $request->get('phone');              
                    $customer->office_id      = Auth::user()->office_id;
                    $customer->place_id = $request->get('place');              
                    $customer->alternate_phone = $request->get('alt_phone');              
                    if ($request->has('customer_type_id')) {
                        $customer->customer_type_id = $request->get('customer_type_id');
                    }
                    $customer->address         = $request->get('address');
                    $customer->save();   
                }
            }
            else{
                // dd("3");
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                    ->where('id','!=',$enquiry->customer_id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){

                    return redirect()->route('enquiry.index')->with('number','true');
                }
                $customer = Customer::firstorcreate([
                                                        'customer_name'  => strtoupper($request->get('name')),
                                                        'customer_phone' => $request->get('phone'),
                                                        'office_id'      => Auth::user()->office_id,
                                                  ]);
                $customer->place_id = $request->get('place');
                $customer->alternate_phone = $request->get('alt_phone');
                if ($request->has('customer_type_id')) {
                    $customer->customer_type_id = $request->get('customer_type_id');
                }
                $customer->address         = $request->get('address');
                $customer->save();
          
            }
            $customer_id = $customer->id;
            $exist       = 1;
        }
        $user = null;
        if(Auth::user()->userpermission(12)) {
            $user = $enquiry->user_id;
        }
        else {
            $user = Auth::user()->id;
        }
        
        $func_date = date('Y-m-d',strtotime($request->get('func_date')));
        $purchase_date = date('Y-m-d',strtotime($request->get('purchase_date')));

        $enquiry->enquiry_status_id = $request->get('status');
        $enquiry->new_exist         = $exist;
        $enquiry->customer_id       = $customer_id;
        $enquiry->user_id           = $user;
        $enquiry->office_id         = Auth::user()->office_id;
        $enquiry->enq_type          = $request->get('enqtype');
        $enquiry->lead_type_id      = $request->get('lead');
        $enquiry->function_date     = $func_date;
        // $enquiry->gender            = $request->get('gen');
        $enquiry->function_type     = $request->get('func_type');
        $enquiry->influencer_type   = $request->get('inf_type');
        $enquiry->associate_id      = $request->get('inf_name');
        $enquiry->address           = $request->get('address');
        $enquiry->reference_name    = $request->get('ref_name');
        $enquiry->reference_phone   = $request->get('ref_phone');
        $enquiry->staff_id          = $request->has('staff_name') ? $request->get('staff_name') : Auth::user()->employee_id;
        $enquiry->purchase_date     = $purchase_date;
        $enquiry->purchase_value    = $request->get('purchase_value');
        $enquiry->bill_no           = $request->get('bill_no');
        $enquiry->coupon_no         = $request->get('coupon_no');

        $enquiry->save();

        EnquiryLog::where('enquiry_id','=',$enquiry->id)
                  ->update(['active' => 'N']);
        EnquiryLog::create([
                            'enquiry_id'        => $enquiry->id,
                            'enquiry_status_id' => $request->get('status'),
                            'status_date'       => $statusdate,
                            'status_remaks'     => ucfirst($request->get('remarks')),
                            'user_id'           => Auth::user()->id,
                            'active'            => 'Y',
                            'office_id'         => Auth::user()->office_id,
                         ]);

    
                         if($request->index) {
                            return redirect()->route('team.enquiry.index')->with('update','true');
                        }
        return redirect()->route('enquiry.index')->with('update','true');
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

    public function checkPhone(Request $request)
    {
        $num  = $request->get('num');
        $cust = Customer::where('customer_phone','=',$num)
                        ->where('office_id','=',Auth::user()->office_id)
                        ->first();
        return $cust;
    }

    public function catProduct(Request $request)
    {
        $cat = $request->get('cat');
        $pro = Product::where('category_id','=',$cat)
                      ->orderBy('name')
                      ->get();

        return $pro;
    }

    public function proUnits(Request $request)
    {
        $pro  = $request->get('pro');
        $unit = PossibleUnit::join('uom','uom.id','=','possible_units.unit_id')
                            ->where('possible_units.product_id','=',$pro)
                            ->selectRaw('possible_units.*,uom.unit')
                            ->orderBy('uom.unit')
                            ->get();

        return $unit;
    }
    
    public function unitPrice(Request $request)
    {
        $unit = $request->get('unit');
        $pro  = $request->get('pro');
        $price = PossibleUnit::where('product_id','=',$pro)
                             ->where('unit_id','=',$unit)
                             ->first();

        return $price;
    }

    public function proStore(Request $request)
    {
        $enq_item_id = $request->get('enq_item_id');
        $enq_id      = $request->get('enq_id');
        $cat         = $request->get('cat');
        $pro         = $request->get('pro');
        $unit        = $request->get('unit');
        $qty         = $request->get('qty');
        $remarks     = $request->get('remarks');
        $unitpice    = $request->get('unitpice');
        $enq_pro     = null;

        if($enq_item_id == null){
            $enq_itm  = EnquiryItem::firstOrCreate([
                                                     'enquiry_id' => $enq_id,
                                                     'product_id' => $pro,
                                                     'unit_id'    => $unit,
                                                 ],[
                                                     'unit_price' => $unitpice,
                                                     'qty'        => $qty,     
                                                     'total'      => $unitpice*$qty,
                                                     'remarks'    => $remarks
                                                 ]);  
        }
        else{
            $enq_itm = EnquiryItem::find($enq_item_id);
            $enq_itm->enquiry_id = $enq_id;
            $enq_itm->product_id = $pro;
            $enq_itm->unit_id    = $unit;
            $enq_itm->unit_price = $unitpice;
            $enq_itm->qty        = $qty;
            $enq_itm->total      = $unitpice*$qty;
            $enq_itm->remarks    = $remarks;
            $enq_itm->save();
        }

        $enq_pro = EnquiryItem::join('products','products.id','=','enquiry_items.product_id')
                              ->join('uom','uom.id','=','enquiry_items.unit_id')
                              ->where('enquiry_items.id','=',$enq_itm->id)
                              ->selectRaw('enquiry_items.*,products.name,uom.unit')
                              ->first();

        return $enq_pro;
    }

    public function proEdit(Request $request)
    {
        $enq_item_id = $request->get('id');
        $enq_item    = EnquiryItem::find($enq_item_id); 
        $cat_id      = Product::find($enq_item->product_id)->category_id;
        $products    = Product::where('category_id','=',$cat_id)
                              ->orderBy('name')
                              ->get();
        $unit        = PossibleUnit::join('uom','uom.id','=','possible_units.unit_id')
                                   ->where('possible_units.product_id','=',$enq_item->product_id)
                                   ->selectRaw('possible_units.*,uom.unit')
                                   ->orderBy('uom.unit')
                                   ->get();
                          
        return [$enq_item,$cat_id,$products,$unit];       
    }

    public function proDelete(Request $request)
    {
        $enq_item_id = $request->get('id');
        EnquiryItem::find($enq_item_id)->delete();

        return 0;
    }

    public function enquirySave(Request $request)
    {
        $enq_id = $request->get('enquiry_id');
        $from   = $request->get('from');
        $to     = $request->get('to');

        $enq                 = Enquiry::find($enq_id);
        $enq->total          = $request->get('total');
        $enq->discount_value = $request->get('discount');
        $enq->sub_total      = $request->get('subtotal');
        $enq->tax_per        = $request->get('tax');
        $enq->tax_amount     = $request->get('taxamt');
        $enq->grand_total    = $request->get('grandtot');
        $enq->save();
        
        $text    = str_replace(' ', '', EnquiryStatus::find($enq->enquiry_status_id)->status_name);
        $statsus = $text.'/'.$from.'/'.$to;
        
        return redirect($statsus)->with('update','true'); 
    }

    public function viewItem($id,$from,$to)
    {   
        $enquiry      = Enquiry::find($id);
        $remarks =null;
        if($enquiry->enquiry_status_id == 4) {
            $remarks = EnquiryLog::where('enquiry_id',$id)->where('enquiry_status_id',4)->first()->status_remaks;
                }
        else if ($enquiry->enquiry_status_id == 5) {
            $remarks = EnquiryLog::where('enquiry_id',$id)->where('enquiry_status_id',5)->first()->status_remaks;

        }
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->where('is_active','=','Y')
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
        $flwdate      = null;
        $dlvdate      = null;

        if($enq_date->status_name == 'FOLLOW UP'){
            $flwdate = $enq_date->status_date;
        }
        if($enq_date->status_name == 'SALE CLOSE'){
            $dlvdate = $enq_date->status_date;
        }
        $text      = str_replace(' ', '', $enquiry->enqStatus->status_name);
        $text      = $text.'/'.$from.'/'.$to;

        $category  = ProductCategory::orderBy('category_name')->get();
        
        $enqpro    = EnquiryItem::join('products','products.id','=','enquiry_items.product_id')
                                ->join('uom','uom.id','=','enquiry_items.unit_id')
                                ->where('enquiry_items.enquiry_id','=',$enquiry->id)
                                ->selectRaw('enquiry_items.*,products.name,uom.unit')
                                ->orderBy('products.name')
                                ->get();

        $quotation = Quotation::where('enquiry_id','=',$id) 
                              ->where('office_id','=',Auth::User()->office_id)
                              ->orderBy('created_at','desc')
                              ->get();
        $associates   = BusinessAssociate::all();
                              // dd($associates);
         $place_id = Customer::where('id',$enquiry->customer_id)->first()->place_id;
         $places  = Places::all();


        return view('enquiry/viewitem',compact('type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','text','from','to','category','enqpro','quotation','func_type','inf_type','associates','place_id','places','remarks'));
    }
    
    public function quotStore(Request $request)
    {
        $quot_no     = $request->get('quot_no');
        $quot_date   = $request->get('quot_date');
        $quot_price  = $request->get('quot_price');
        $quot_enq_id = $request->get('quot_enq_id');

        Quotation::where('enquiry_id','=',$quot_enq_id)
                 ->update([
                            'is_active' => 'N'
                        ]);
        Quotation::create([
                            'enquiry_id'    => $quot_enq_id,
                            'quotation_no'  => $quot_no,
                            'date'          => date('Y-m-d',strtotime($quot_date)),
                            'price'         => $quot_price,
                            'is_active'     => 'Y',
                            'office_id'     => Auth::User()->office_id
                        ]);

        $quotation = Quotation::where('enquiry_id','=',$quot_enq_id)
                              ->where('office_id','=',Auth::User()->office_id)
                              ->orderBy('created_at','desc')
                              ->get();

        return $quotation;
    }

    public function quotCheck(Request $request)
    {
        $qno   = $request->get('qno');
        $check = Quotation::where('quotation_no','=',$qno)
                          ->where('office_id','=',Auth::User()->office_id)
                          ->count();

        return $check;
    }



    public function followupShow(Request $request) {
        $from      = date('2025-01-01');//date('Y-m-01');
        if($request->get('from') != null){
            $from = $request->get('from');
           
        }
        $to        = date('Y-m-t');
        if($request->get('to') != null){
            $to = $request->get('to');
        }


        $role = Auth::user()->userRole->role->name;

        $followups = null;
        if(Auth::user()->userpermission(12)) {

                $followups   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                ->where('enquiry_logs.enquiry_status_id','=',2)
                ->where('enquiry.office_id','=',Auth::user()->office_id)
                ->whereBetween('enquiry_logs.status_date', [$from, $to])
                ->where('enquiry_logs.active','=','Y')
                ->orderBy('enquiry_logs.status_date','desc')
                ->selectRaw('enquiry.*,enquiry_logs.status_date')
                ->get(); 
                
        }
        else {
            $followups   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
            ->where('enquiry_logs.enquiry_status_id','=',2)
            ->where('enquiry_logs.user_id','=',Auth::user()->id)
            ->where('enquiry.office_id','=',Auth::user()->office_id)
            ->whereBetween('enquiry_logs.status_date', [$from, $to])
            ->where('enquiry_logs.active','=','Y')
            ->orderBy('enquiry_logs.status_date','desc')
            ->selectRaw('enquiry.*,enquiry_logs.status_date')
            ->get(); 
        }
        $text      = '/FOLLOWUP/'.$from.'/'.$to;
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();

        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->where('is_active','=','Y')
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                ->get();
        $func_type    = FunctionType::all();

        $inf_type     = InfluencerType::all();

        $associates   = BusinessAssociate::all();
        $places  = Places::all();
        $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
        ->where('designations.designation','SALES EXECUTIVE')
        ->select('employee_details.id','employee_details.name')
        ->get();

        $role = Auth::user()->userRole->role->name;
        $staff_id = NULL;
        if(!($role == "ADMIN" || $role == "COORDINATOR")) {
            $staff_id = Auth::user()->employee->id;
        }
        return view('followups/index',compact('followups','from','to','text','status','type','lead_type','func_type','inf_type','places','employee','staff_id','associates'));
    }

    public function getFollowups(Request $request) {
        $fol_id   = $request->get('id');
        // $enq_log  = EnquiryLog::join('enquiry_status','enquiry_status.id','=','enquiry_logs.enquiry_status_id')
        //                       ->where('enquiry_logs.enquiry_id','=',$fol_id)
        //                       ->orderBy('enquiry_logs.created_at','desc')
        //                       ->selectRaw('enquiry_logs.*,enquiry_status.status_name')
        //                       ->get();
        // $follow_ups = Enquiry::find($fol_id);
        $follow_ups = Enquiry::join('customers','customers.id','=','enquiry.customer_id')
                            ->where('enquiry.id',$fol_id)
                            ->selectRaw('customers.*,enquiry.*')
                            ->first();
        $enq_date     = EnquiryLog::join('enquiry_status','enquiry_status.id','=','enquiry_logs.enquiry_status_id')
                                    ->where('enquiry_logs.enquiry_id','=',$fol_id)
                                    ->where('enquiry_logs.active','=','Y')
                                    ->where('enquiry_logs.office_id','=',Auth::user()->office_id)
                                    ->selectRaw('enquiry_logs.*,enquiry_status.status_name')
                                    ->first();
        $remarks = $enq_date->status_remaks;
        $flwdate      = null;
        $dlvdate      = null;
        if($enq_date->status_name == 'FOLLOW UP'){
            $flwdate = $enq_date->status_date;
        }

        if($enq_date->status_name == 'SALE CLOSE'){
            $dlvdate = $enq_date->status_date;
        }
        $follow_ups['flawdate'] =  $flwdate;
        $follow_ups['dlvdate'] =  $dlvdate;
        $follow_ups['remarks'] =  $remarks;
        return $follow_ups;
    }

    public function updateFolloup(Request $request)
    {  
        $from       = $request->get('from');
        $to         = $request->get('to');
        $statusdate = date('Y-m-d');
        $enquiry    = Enquiry::find($request->get('edit_id'));
        $stat       = EnquiryStatus::find($request->get('status'))->status_name;
        if($request->get('flwdate') != null && $stat == 'FOLLOW UP'){
            $statusdate = date('Y-m-d',strtotime($request->get('flwdate')));
        }

        if($request->get('deliverydate') != null && $stat == 'SALE CLOSE'){
            $statusdate = date('Y-m-d',strtotime($request->get('deliverydate')));
        }
        if($request->get('search') == null){
            $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                              ->where('id','!=',$enquiry->customer_id) //exception takinf customer id directly from enquiry
                              ->where('office_id','=',Auth::user()->office_id)
                              ->count();
            if($check2 > 0){
                // return redirect()->route('enquiry.index')->with('number','true');
                return redirect()->route('followup.edit',[$enquiry->id,$from,$to])->with('number','true');

            }
            $customer = Customer::firstorcreate([
                                                    'customer_name'  => strtoupper($request->get('name')),
                                                    'customer_phone' => $request->get('phone'),
                                                    'office_id'      => Auth::user()->office_id,
                                               ]);
            $customer->place_id = $request->get('place');
            $customer->alternate_phone = $request->get('alt_phone');
            if ($request->has('customer_type_id')) {
                $customer->customer_type_id = $request->get('customer_type_id');
            }
            $customer->address        = $request->get('address');
            $customer->save();
            $customer_id = $customer->id;
            $exist       = 0;
        }
        else{
            if ( strpos( $request->get('search'), '/' ) !== false ) {
                $info     = explode( '/', $request->get('search') );
                $customer = Customer::where('customer_name','=',$info[0])
                                    ->where('customer_phone','=',$info[1])
                                    ->where('office_id','=',Auth::user()->office_id)
                                    ->first();
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                  ->where('id','!=',$customer->id)
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    // return redirect()->route('enquiry.index')->with('number','true');

                    return redirect()->route('followup.edit',[$enquiry->id,$from,$to])->with('number','true');

                }
                if($customer != null){    
                    $customer->customer_name  = strtoupper($request->get('name'));              
                    $customer->customer_phone = $request->get('phone');              
                    $customer->office_id      = Auth::user()->office_id;
                    $customer->place_id = $request->get('place');    
                    $customer->alternate_phone = $request->get('alt_phone');  
                    if ($request->has('customer_type_id')) {
                        $customer->customer_type_id = $request->get('customer_type_id');
                    }
                    $customer->address         = $request->get('address');                     
                    $customer->save();   
                }
            }
            else{
                $check2 = Customer::where('customer_phone','=',$request->get('phone'))
                                  ->where('office_id','=',Auth::user()->office_id)
                                  ->count();
                if($check2 > 0){
                    return redirect()->route('enquiry.index')->with('number','true');
                }
                $customer = Customer::firstorcreate([
                                                        'customer_name'  => strtoupper($request->get('name')),
                                                        'customer_phone' => $request->get('phone'),
                                                        'office_id'      => Auth::user()->office_id,

                                                  ]);
                $customer->place_id = $request->get('place');
                $customer->alternate_phone = $request->get('alt_phone');
                if ($request->has('customer_type_id')) {
                    $customer->customer_type_id = $request->get('customer_type_id');
                }
                $customer->address         = $request->get('address');
                $customer->save();
            }
            $customer_id = $customer->id;
            $exist       = 1;
        }
        if(Auth::user()->userpermission(12)) {
            $user = $enquiry->user_id;
        }
        else {
            $user = Auth::user()->id;
        }
        $func_date = date('Y-m-d',strtotime($request->get('func_date')));
        $purchase_date = date('Y-m-d',strtotime($request->get('purchase_date')));

        $enquiry->enquiry_status_id = $request->get('status');
        $enquiry->new_exist         = $exist;
        $enquiry->customer_id       = $customer_id;
        $enquiry->user_id           = $user;
        $enquiry->office_id         = Auth::user()->office_id;
        $enquiry->enq_type          = $request->get('enqtype');
        $enquiry->lead_type_id      = $request->get('lead');
        $enquiry->lead_type_id      = $request->get('lead');
        $enquiry->function_date     = $func_date;
        // $enquiry->gender            = $request->get('gen');
        $enquiry->function_type     = $request->get('func_type');
        $enquiry->influencer_type   = $request->get('inf_type');
        $enquiry->associate_id      = $request->get('inf_name');
        $enquiry->address           = $request->get('address');
        $enquiry->reference_name    = $request->get('ref_name');
        $enquiry->reference_phone   = $request->get('ref_phone');
        $enquiry->staff_id          = $request->has('staff_name') ? $request->get('staff_name') : Auth::user()->employee_id;
        $enquiry->purchase_date     = $purchase_date;
        $enquiry->purchase_value    = $request->get('purchase_value');
        $enquiry->bill_no           = $request->get('bill_no');
        $enquiry->coupon_no         = $request->get('coupon_no');
        $enquiry->save();
        EnquiryLog::where('enquiry_id','=',$enquiry->id)
                  ->update(['active' => 'N']);
        EnquiryLog::create([
                            'enquiry_id'        => $enquiry->id,
                            'enquiry_status_id' => $request->get('status'),
                            'status_date'       => $statusdate,
                            'status_remaks'     => $request->get('remarks'),
                            'user_id'           => Auth::user()->id,
                            'active'            => 'Y',
                            'office_id'         => Auth::user()->office_id,
                         ]);

        return redirect()->route('followup.edit',[$enquiry->id,$from,$to])->with('update','true');
    }

    public function editFollowup($id,$from,$to)
    {
        
        $followups   = Enquiry::where('enquiry_status_id','=',2)
                            ->where('user_id','=',Auth::user()->id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();
        $text      = '/FOLLOWUP/'.$from.'/'.$to;
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();

        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                ->where('is_active','=','Y')
                                ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                ->get();
        $func_type    = FunctionType::all();

        $inf_type     = InfluencerType::all();

        $places  = Places::all();
        $associates   = BusinessAssociate::all();

        $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
        ->where('designations.designation','SALES EXECUTIVE')
        ->select('employee_details.id','employee_details.name')
        ->get();

        $role = Auth::user()->userRole->role->name;
        $staff_id = NULL;
        if(!($role == "ADMIN" || $role == "COORDINATOR")) {
            $staff_id = Auth::user()->employee->id;
        }                        

        return view('followups/index',compact('associates','followups','from','to','text','status','type','lead_type','func_type','inf_type','places','employee','staff_id'));
    }

    public function searchFollowups(Request $request) {

        
        $from      = date('2025-01-01');//date('Y-m-01');
        if($request->get('from') != null){
            $from = $request->get('from');
            $from = date('Y-m-d', strtotime($from));
        }
        $to        = date('Y-m-t');
        if($request->get('to') != null){
            $to = $request->get('to');
            $to = date('Y-m-d', strtotime($to));

        }
        $associates   = BusinessAssociate::all();


        // $followups   = Enquiry::where('enquiry_status_id','=',2)
        //                     ->where('user_id','=',Auth::user()->id)
        //                     ->where('office_id','=',Auth::user()->office_id)
        //                     ->whereBetween('enq_date', [$from, $to])
        //                     ->orderBy('created_at','desc')
        //                     ->get();


        $role = Auth::user()->userRole->role->name;

        $followups = null;
        if(Auth::user()->userpermission(10)) {

                $followups   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                ->where('enquiry_logs.enquiry_status_id','=',2)
                ->where('enquiry.office_id','=',Auth::user()->office_id)
                ->whereBetween('enquiry_logs.status_date', [$from, $to])
                ->where('enquiry_logs.active','=','Y')
                ->orderBy('enquiry_logs.status_date','desc')
                ->selectRaw('enquiry.*,enquiry_logs.status_date')
                ->get(); 
                
        }
        else {
            $followups   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
            ->where('enquiry_logs.enquiry_status_id','=',2)
            ->where('enquiry_logs.user_id','=',Auth::user()->id)
            ->where('enquiry.office_id','=',Auth::user()->office_id)
            ->whereBetween('enquiry_logs.status_date', [$from, $to])
            ->where('enquiry_logs.active','=','Y')
            ->orderBy('enquiry_logs.status_date','desc')
            ->selectRaw('enquiry.*,enquiry_logs.status_date')
            ->get(); 
        }
        $text      = '/FOLLOWUP/'.$from.'/'.$to;
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();

        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->where('is_active','=','Y')                         
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                ->get();
        $func_type    = FunctionType::all();

        $inf_type     = InfluencerType::all();

        $places  = Places::all();
        $employee  = EmployeeDetail::join('designations','employee_details.designation_id','=','designations.id')
        ->where('designations.designation','SALES EXECUTIVE')
        ->select('employee_details.id','employee_details.name')
        ->get();

        $role = Auth::user()->userRole->role->name;
        $staff_id = NULL;
        if(!($role == "ADMIN" || $role == "COORDINATOR")) {
            $staff_id = Auth::user()->employee->id;
        }

        return view('followups/index',compact('associates','followups','from','to','text','status','type','lead_type','func_type','inf_type','places','employee','staff_id'));
    } 


    public function addPlace(Request $request)
    {
        $place = $request->get('place');
        $count = Places::where('place',$place)->count();
        $data = 0;
        if($count > 0) {
            $data  = 0 ;
        }
        else {
            $plc = new Places;
            $plc->place = strtoupper($place);
            $plc->save();
            $data =  $plc->id;
        }
      return $data;
    }


    public function selectPlace() {
        $places = Places::orderBy('created_at', 'desc')->first();
        return $places;
    }

    public function addFunction(Request $request)
    {
        $nfunction = $request->get('nfunction');
        $count = FunctionType::where('function_type',$nfunction)->count();
        $data = null;
        if($count > 0) {
            $data  = 0 ;
        }
        else {
            $func = new FunctionType;
            $func->function_type = strtoupper($nfunction);
            $func->save(); 
            $data =  $func->id;
    
        }
        return $data;
    }
    
}