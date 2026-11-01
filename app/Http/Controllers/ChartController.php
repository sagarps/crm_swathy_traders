<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\EmployeeDetail;
use App\Customer;
use App\EnquiryStatus;
use App\Enquiry;
use App\EnquiryLog;
use App\TeamMapping;
use Auth;
use App\Places;
use App\BusinessAssociate;
class ChartController extends Controller
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
    public function saleChart()
    {
        $mnth     = date('n') +1;
        $year     = date('Y') -1;
        $yearshow = date('y');

        $months = array(
           1 => 'January',
           2 => 'February',
           3 => 'March',
           4 => 'April',
           5 => 'May',
           6 => 'June',
           7 => 'July ',
           8 => 'August',
           9 => 'September',
           10 => 'October',
           11 => 'November',
           12 => 'December',
        );
        $j = $mnth;

        for($i = 1 ;$i <= 12 ; $i++)
        {

          if($j > 12)
          {
            $j = 1;
            $year = $year +1;
          }

          $enqcount  = Enquiry::where('office_id','=', Auth::user()->office_id)
                              ->whereMonth('enq_date','=',$j)
                              ->whereYear('enq_date','=',$year)
                              ->count();

          $sale_clo  = EnquiryStatus::where('status_name','=','SALE CLOSE')
                                    ->first()->id;

          $salecount = EnquiryLog::where('office_id','=', Auth::user()->office_id)
                                 ->where('enquiry_status_id','=',$sale_clo)
                                 ->where('active','=','Y')
                                 ->whereMonth('created_at','=',$j)
                                 ->whereYear('created_at','=',$year)
                                 ->count();

          $show_string =  substr($months[$j], 0, 3);
          $response[$show_string]['enqcount'] = intval($enqcount);
          $response[$show_string]['salecount'] = intval($salecount);

          $j++;
        }

        return json_encode([$response]);
    }


    public function pieChart(){
        // $response = BusinessAssociate::join('places','business_associate.place_id','places.id')
        // ->join('enquiry','business_associate.id','enquiry.associate_id')
        // ->selectRaw('places.place,count(enquiry.enq_no) as totlead')->groupBy('places.place')
        // ->orderBy('totlead','desc')->get();

        $place_lead=Enquiry::join('business_associate','enquiry.associate_id','business_associate.id')
        ->join('places','business_associate.place_id','places.id')
        ->where('enquiry.office_id','=',Auth::user()->office_id)
        ->selectRaw('count(enquiry.enq_no) as value,places.place as label')
        ->groupBy('places.place')->orderBy('value','Desc')->get();

        function random_part() {
          return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
      }

      function randomHEX($amount) {
        $_result = "";
        for ($i = 0 ; $i < $amount; $i++) {
          $_result .= random_part();
        }
        return $_result;
      }

      //to get a 16 HEX number


        foreach ($place_lead as $pdata){
        $color='#'.randomHEX(3);
         $pdata->color=$color;
         $pdata->highlight=$color;

        }


            return json_encode($place_lead);

    }

}
