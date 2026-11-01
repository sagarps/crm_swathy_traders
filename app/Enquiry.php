<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Enquiry extends Model
{
    use LogsActivity;

    protected $table = 'enquiry';

    protected $fillable = [
        'enq_date','enq_no','enquiry_status_id','new_exist','customer_id','user_id','office_id','total','discount_value','sub_total','tax_per','tax_amount','grand_total','enq_type','lead_type_id','other_user_id',
    'function_date','gender','function_type','influencer_type','associate_id','address','reference_name','reference_phone','staff_id','purchase_date','purchase_value','bill_no','coupon_no'

    ];

    public $timestamps = true;

    public function customer()
    {
    	return $this->belongsTo('App\Customer','customer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function enqStatus()
    {
    	return $this->belongsTo('App\EnquiryStatus','enquiry_status_id');
    }

    public function lead()
    {
        return $this->belongsTo('App\CommonMaster','lead_type_id');
    }

    public function type()
    {
        return $this->belongsTo('App\CommonMaster','enq_type');
    }

    public function employee()
    {
        return $this->belongsTo('App\EmployeeDetail','staff_id');
    }

    public function influencerType()
    {
        return $this->belongsTo('App\InfluencerType','influencer_type')->withDefault(['influencer_type' => 'NIL']);
    }

    public function businessAssociate()
    {
        return $this->belongsTo('App\BusinessAssociate','associate_id')->withDefault(['associate_name' => 'NIL']);
    }



    public function associate_type()
    {
    	return $this->belongsTo('App\InfluencerType','influencer_type');
    }
    public function totlead($from,$to,$acc_id){

       $enquiry= Enquiry::whereBetween('enq_date',[$from,$to])
               ->where('associate_id',$acc_id)->get();
                            return count($enquiry);
    }
    public function totCnvrt($from,$to,$acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('associate_id',$acc_id)
                            ->get();
                            return count($enquiry);
    }

    public function totLost($from,$to,$acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','LOST')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('associate_id',$acc_id)
                            ->get();
                            return count($enquiry);
    }

    public function totfollowup($from,$to,$acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','FOLLOW UP')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('associate_id',$acc_id)
                            ->get();
                            return count($enquiry);
    }
    public function convertPer($from,$to,$acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();
        $conper = 0;
        $totenquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('associate_id',$acc_id)->count();
        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                ->where('enquiry_status_id',$status->id)
                ->where('associate_id',$acc_id)
                ->count();
        if($totenquiry != 0) {
            $conper=$enquiry/$totenquiry*100;
        }
        
       return round($conper,2);
    }

    public function hmconvertPer($acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();

        $totenquiry = Enquiry::count();
        $enquiry = Enquiry::where('enquiry_status_id',$status->id)
        ->where('associate_id',$acc_id)->count();
       $conper= 0;
       if($totenquiry != 0) {
        $conper=$enquiry/$totenquiry*100;
       }
       return round($conper,2);
    }

    public function Totfeebee($from,$to,$acc_id){
        $feebee=FreebieIssue::whereBetween('freebie_issue_date',[$from,$to])
                             ->where('associate_name_id',$acc_id)->count();
       return $feebee;
    }
    public function feebeeVal($from,$to,$acc_id){
        $feebee=FreebieIssue::whereBetween('freebie_issue_date',[$from,$to])
                             ->where('associate_name_id',$acc_id)->sum('freebie_value');
       return $feebee;
    }

    public function sttotCnvrt($from,$to,$st_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('staff_id',$st_id)
                            ->get();
                            return count($enquiry);
    }

    public function sttotLost($from,$to,$st_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','LOST')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('staff_id',$st_id)
                            ->get();
                            return count($enquiry);
    }

    public function sttotfollowup($from,$to,$st_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','FOLLOW UP')->first();

        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('staff_id',$st_id)
                            ->get();
                            return count($enquiry);
    }
    public function stconvertPer($from,$to,$st_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();

        $totenquiry = Enquiry::whereBetween('enq_date',[$from,$to])
                        ->where('staff_id',$st_id)->count();
        $enquiry = Enquiry::whereBetween('enq_date',[$from,$to])
        ->where('enquiry_status_id',$status->id)
        ->where('staff_id',$st_id)
        ->count();
       $conper=0;
       if($totenquiry!=0) {
        $conper=$enquiry/$totenquiry*100;
       }
       
       return round($conper,2);
    }

    public function totPurchValue($from,$to,$acc_id){
        $status       = EnquiryStatus::where('is_active','=','Y')->where('status_name','SALE CLOSE')->first();

        $purchase_value = Enquiry::whereBetween('enq_date',[$from,$to])
                            ->where('enquiry_status_id',$status->id)
                            ->where('associate_id',$acc_id)
                            ->sum('purchase_value');
                            return $purchase_value;
    }

    public function staffs($from,$to,$acc_id){
        $enquiry= Enquiry::join('employee_details','enquiry.staff_id','employee_details.id')
        ->whereBetween('enq_date',[$from,$to])
        ->where('associate_id',$acc_id)->select('employee_details.name')->groupBy('name')->pluck('name');

       return $enquiry;
    }

    public function enquiryItem()
    {
        return $this->hasMany('App\EnquiryItem','enquiry_id');
    }
}
