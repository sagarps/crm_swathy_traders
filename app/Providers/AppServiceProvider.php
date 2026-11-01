<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\EnquiryStatus;
use App\EnquiryLog;
use App\Office;
use Auth;
use App\Helpers\Helper;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer('*', function($view)
        {
            $escalated_count = 0;
            $trialStatus=[];
            if (auth()->check()) {
                $status_id = EnquiryStatus::where('status_name','=','FOLLOW UP')
                                  ->first()->id;
                $escalated_count = EnquiryLog::join('enquiry','enquiry.id','=','enquiry_logs.enquiry_id')
                             ->where('enquiry_logs.office_id','=',Auth::User()->office_id)
                             ->where('active','=','Y') 
                             ->where('enquiry_logs.enquiry_status_id','=',$status_id)
                             ->where('status_date','<',date('Y-m-d'))
                            //   ->where('enquiry.staff_id','=',Auth::User()->id)
                             ->where('enquiry_logs.user_id','=',Auth::User()->id)
                             ->count();
                $user = Office::find(Auth::user()->office_id);
                $trialStatus = Helper::getTrialStatus($user);
            }
            
                            
            $view->with('escalated_count', $escalated_count)
            ->with('trialStatus', $trialStatus);
        });
    }
}
