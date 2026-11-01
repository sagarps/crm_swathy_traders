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
use App\User;
use App\Quotation;
use Auth;

class AdminEnquiryController extends Controller
{
    public function index(Request $request)
    {    

        $from      = date('Y-m-01');
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
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('other_user_id','=',Auth::user()->id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();

        $text      = 'admin/NEWLEAD/'.$from.'/'.$to;
        
        return view('admin_enquiry/index',compact('status','status_id','enquiry','from','to','text'));
    }

    public function newLead(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                  ->orderBy('seq')
                                  ->get();
        $status_id = $status->where('status_name','=','NEW LEAD')
                            ->first()->id;
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('other_user_id','=',Auth::user()->id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();

        $text      = 'admin/NEWLEAD/'.$from.'/'.$to;
        
        return view('admin_enquiry/index',compact('status','status_id','enquiry','from','to','text'));
    }

    public function followUP(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','FOLLOW UP')
                            ->first()->id;

        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                          /*  ->where('enquiry_logs.other_user_id','=',Auth::user()->id)*/
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereBetween('enquiry_logs.status_date', [$from, $to])
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get(); 

        $text      = 'admin/FOLLOWUP/'.$from.'/'.$to;
        
        return view('admin_enquiry/followupindex',compact('status','status_id','enquiry','from','to','text'));
    }

    public function saleClose(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','SALE CLOSE')
                            ->first()->id;

        $enquiry   = Enquiry::join('enquiry_logs','enquiry_logs.enquiry_id','=','enquiry.id')
                            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                            ->where('enquiry_logs.other_user_id','=',Auth::user()->id)
                            ->where('enquiry.office_id','=',Auth::user()->office_id)
                            ->whereDate('enquiry_logs.created_at','>=',$from)
                            ->whereDate('enquiry_logs.created_at','<=',$to)
                            ->where('enquiry_logs.active','=','Y')
                            ->orderBy('enquiry_logs.status_date','desc')
                            ->selectRaw('enquiry.*,enquiry_logs.status_date')
                            ->get();

        $text      = 'admin/SALECLOSE/'.$from.'/'.$to;
        
        return view('admin_enquiry/salecloseindex',compact('status','status_id','enquiry','from','to','text'));
    }

    public function cancel(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','CANCEL')
                            ->first()->id;
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('other_user_id','=',Auth::user()->id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();

        $text      = 'admin/CANCEL/'.$from.'/'.$to;
        
        return view('admin_enquiry/cancelindex',compact('status','status_id','enquiry','from','to','text'));
    }

    public function lost(Request $request,$from,$to)
    {
        $status    = EnquiryStatus::where('is_active','=','Y')
                                ->orderBy('seq')
                                ->get();
        $status_id = $status->where('status_name','=','LOST')
                            ->first()->id;
        $enquiry   = Enquiry::where('enquiry_status_id','=',$status_id)
                            ->where('other_user_id','=',Auth::user()->id)
                            ->where('office_id','=',Auth::user()->office_id)
                            ->whereBetween('enq_date', [$from, $to])
                            ->orderBy('created_at','desc')
                            ->get();

        $text      = 'admin/LOST/'.$from.'/'.$to;
        
        return view('admin_enquiry/lostindex',compact('status','status_id','enquiry','from','to','text'));
    }




    public function create($from,$to)
    {   
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                    ->get();
        $status       = EnquiryStatus::where('is_active','=','Y')
                                     ->orderBy('seq')
                                     ->get();
        $staff        = User::join('employee_details','employee_details.id','=','users.employee_id')
                            ->where('users.office_id','=',Auth::user()->office_id)
                            ->selectRaw('users.id,employee_details.name')
                            ->get();
        
        return view('admin_enquiry/create',compact('type','lead_type','status','from','to','staff'));
    }

    public function store(Request $request)
    {    
        $statusdate = date('Y-m-d');
        $from       = $request->get('from');
        $to         = $request->get('to');
        $stat       = EnquiryStatus::find($request->get('status'))->status_name;
        $staff      = $request->get('staff');

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
                                                    'office_id'      => Auth::user()->office_id
                                               ]);
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
                    $customer->save();   
                }
                else{
                    $customer = Customer::firstorcreate([
                                                            'customer_name'  => strtoupper($request->get('name')),
                                                            'customer_phone' => $request->get('phone'),
                                                            'office_id'      => Auth::user()->office_id
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
                                                        'office_id'      => Auth::user()->office_id
                                                  ]);
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

        $enquiry = Enquiry::create([
                                    'enq_date'          => date('Y-m-d'),
                                    'enq_no'            => $num,
                                    'enquiry_status_id' => $request->get('status'),
                                    'new_exist'         => $exist,
                                    'customer_id'       => $customer_id,
                                    'user_id'           => $staff,
                                    'office_id'         => Auth::user()->office_id,
                                    'enq_type'          => $request->get('enqtype'),
                                    'lead_type_id'      => $request->get('lead'),
                                    'other_user_id'     => Auth::user()->id
                                  ]);
        EnquiryLog::where('enquiry_id','=',$enquiry->id)
                  ->update(['active' => 'N']);
        EnquiryLog::create([
                            'enquiry_id'        => $enquiry->id,
                            'enquiry_status_id' => $request->get('status'),
                            'status_date'       => $statusdate,
                            'status_remaks'     => $request->get('remarks'),
                            'user_id'           => $staff,
                            'active'            => 'Y',
                            'office_id'         => Auth::user()->office_id,
                            'other_user_id'     => Auth::user()->id
                         ]);
        
        return redirect()->route('admin.enquiry.edit',[$enquiry->id,$from,$to])->with('save','true');
    }

    public function customerList(Request $request)
    { 
        $item = Customer::where('office_id','=',Auth::user()->office_id)
                        ->where('customer_name','like','%'.$request->get('term').'%')
                        ->orWhere('customer_phone','like','%'.$request->get('term').'%')
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

    public function edit($id,$from,$to)
    {   

        $enquiry      = Enquiry::find($id);
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                    ->get();
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
        $flwdate      = null;
        $dlvdate      = null;

        if($enq_date->status_name == 'FOLLOW UP'){
            $flwdate = $enq_date->status_date;
        }
        if($enq_date->status_name == 'SALE CLOSE'){
            $dlvdate = $enq_date->status_date;
        }
        $text      = str_replace(' ', '', $enquiry->enqStatus->status_name);
        $text      = 'admin/'.$text.'/'.$from.'/'.$to;

        /*product details*/
        $category  = ProductCategory::orderBy('category_name')->get();
        /*$enqpro    = EnquiryItem::where('enquiry_id','=',$enquiry->id)
                                ->get();*/
        $enqpro    = EnquiryItem::join('products','products.id','=','enquiry_items.product_id')
                                ->join('uom','uom.id','=','enquiry_items.unit_id')
                                ->where('enquiry_items.enquiry_id','=',$enquiry->id)
                                ->selectRaw('enquiry_items.*,products.name,uom.unit')
                                ->orderBy('products.name')
                                ->get();

        $staff     = User::join('employee_details','employee_details.id','=','users.employee_id')
                         ->where('users.office_id','=',Auth::user()->office_id)
                         ->selectRaw('users.id,employee_details.name')
                         ->get();

        $quotation = Quotation::where('enquiry_id','=',$id) 
                              ->where('office_id','=',Auth::User()->office_id)
                              ->orderBy('created_at','desc')
                              ->get();

        return view('admin_enquiry/edit',compact('type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','text','from','to','category','enqpro','staff','quotation'));
    }

    public function update(Request $request)
    {   
        $from       = $request->get('from');
        $to         = $request->get('to');
        $statusdate = date('Y-m-d');
        $staff      = $request->get('staff');
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
                return redirect()->route('enquiry.index')->with('number','true');
            }
            $customer = Customer::firstorcreate([
                                                    'customer_name'  => strtoupper($request->get('name')),
                                                    'customer_phone' => $request->get('phone'),
                                                    'office_id'      => Auth::user()->office_id
                                               ]);
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
                                                        'office_id'      => Auth::user()->office_id
                                                  ]);
            }
            $customer_id = $customer->id;
            $exist       = 1;
        }

        $enquiry->enquiry_status_id = $request->get('status');
        $enquiry->new_exist         = $exist;
        $enquiry->customer_id       = $customer_id;
        $enquiry->user_id           = $staff;
        $enquiry->office_id         = Auth::user()->office_id;
        $enquiry->enq_type          = $request->get('enqtype');
        $enquiry->lead_type_id      = $request->get('lead');
        $enquiry->other_user_id     = Auth::user()->id;
        $enquiry->save();

        EnquiryLog::where('enquiry_id','=',$enquiry->id)
                  ->update([
                            'active'        => 'N',
                            'user_id'       => $staff,
                            'other_user_id' => Auth::User()->id
                         ]);
        EnquiryLog::create([
                            'enquiry_id'        => $enquiry->id,
                            'enquiry_status_id' => $request->get('status'),
                            'status_date'       => $statusdate,
                            'status_remaks'     => $request->get('remarks'),
                            'user_id'           => $staff,
                            'active'            => 'Y',
                            'office_id'         => Auth::user()->office_id,
                            'other_user_id'     => Auth::User()->id
                         ]);

        return redirect()->route('admin.enquiry.edit',[$enquiry->id,$from,$to])->with('update','true');
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
        $statsus = 'admin/'.$text.'/'.$from.'/'.$to;
        
        return redirect($statsus)->with('update','true'); 
    }

    public function viewItem($id,$from,$to)
    {   
        $enquiry      = Enquiry::find($id);
        $enq_type_id  = CommonMaster::where('name','=','ENQUIRY TYPE')
                                    ->first()->id;
        $type         = CommonMaster::where('parent_id','=',$enq_type_id)
                                    ->get();
        $lead_type_id = CommonMaster::where('name','=','LEAD TYPE')
                                    ->first()->id;
        $lead_type    = CommonMaster::where('parent_id','=',$lead_type_id)
                                    ->get();
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

        $staff        = User::join('employee_details','employee_details.id','=','users.employee_id')
                            ->where('users.office_id','=',Auth::user()->office_id)
                            ->selectRaw('users.id,employee_details.name')
                            ->get();

        if($enq_date->status_name == 'FOLLOW UP'){
            $flwdate = $enq_date->status_date;
        }
        if($enq_date->status_name == 'SALE CLOSE'){
            $dlvdate = $enq_date->status_date;
        }
        $text      = str_replace(' ', '', $enquiry->enqStatus->status_name);
        $text      = 'admin/'.$text.'/'.$from.'/'.$to;

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

        return view('admin_enquiry/viewitem',compact('type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','text','from','to','category','enqpro','staff','quotation'));
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
}