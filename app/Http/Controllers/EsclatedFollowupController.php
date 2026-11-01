<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use App\EnquiryLog;
use App\EnquiryStatus;
use App\Enquiry;
use App\CommonMaster;
use App\ProductCategory;
use App\EnquiryItem;
use App\Customer;
use Auth;

class EsclatedFollowupController extends Controller
{
    
    public function index()
    {   
        $status_id = EnquiryStatus::where('status_name','=','FOLLOW UP')
                                  ->first()->id;
        $role = Auth::user()->userRole->role->name;
        if($role == "ADMIN" || $role == "COORDINATOR") {
            $emquiry = EnquiryLog::join('enquiry','enquiry.id','=','enquiry_logs.enquiry_id')
            ->where('enquiry_logs.office_id','=',Auth::User()->office_id)
            ->where('enquiry_logs.user_id','=',Auth::User()->id)
            ->where('active','=','Y') 
            ->where('enquiry_logs.enquiry_status_id','=',$status_id)
            ->where('status_date','<',date('Y-m-d'))
            ->get();
        } 
        else {
        $emquiry = EnquiryLog::join('enquiry','enquiry.id','=','enquiry_logs.enquiry_id')
                             ->where('enquiry_logs.office_id','=',Auth::User()->office_id)
                             ->where('active','=','Y') 
                             ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                             ->where('status_date','<',date('Y-m-d'))
                              ->where('enquiry.staff_id','=',Auth::User()->employee_id)
                            //  ->where('enquiry_logs.user_id','=',Auth::User()->id)
                             ->get();
                            

        }
        // dd(Auth::User()->id);
        return view('esclatedfollowup/index',compact('emquiry'));
    }

    public function edit($id)
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

        return view('esclatedfollowup/edit',compact('type','lead_type','status','enquiry','enq_log','flwdate','dlvdate','category','enqpro'));
    }

    public function update(Request $request)
    {   
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
        $enquiry->user_id           = Auth::user()->id;
        $enquiry->office_id         = Auth::user()->office_id;
        $enquiry->enq_type          = $request->get('enqtype');
        $enquiry->lead_type_id      = $request->get('lead');
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

        return redirect()->route('escalated.followup.edit',$enquiry->id)->with('update','true');
    }

    public function esclatedSave(Request $request)
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
        
        return redirect()->route('escalated.followup.index')->with('update','true');
    }
}