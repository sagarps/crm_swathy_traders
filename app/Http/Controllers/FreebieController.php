<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Freebie;
use App\FreebieIssue;
use App\InfluencerType;
use App\Places;
use App\BusinessAssociate;
use DateTime;




class FreebieController extends Controller
{
    public function createFreebie(Request $request)
    {
        $inf_type     = InfluencerType::all();
        $places  = Places::all();
        $freebie_types = Freebie::all();

        $ass_names = BusinessAssociate::all();

        return view('freebie/index',compact('inf_type','places','freebie_types','ass_names'));
    }

    public function addFreebieIssueType(Request $request)
    {
        $freebie = $request->get('freebie');
        $count = Freebie::where('freebie_type',$freebie)->count();
        $data = 0;
        if($count > 0) {
            $data  = 0 ;
        }
        else {
            $data = 1;
            $frb = new Freebie;
            $frb->freebie_type = strtoupper($freebie);
            $frb->save();
            $data = $frb->id;
        }
      return $data;
    }

    public function create(Request $request)
    {
        $freebie_date = date('Y-m-d',strtotime($request->get('free_date')));
        $enquiry = FreebieIssue::create([
            'associate_type_id' => $request->get('inf_type'),
            'associate_name_id' => $request->get('inf_name'),
            'contact_person'    => strtoupper($request->get('cont_person')),
            'contact_no'        => $request->get('cont_no'),
            'place_id'          => $request->get('place'),
            'address'           => $request->get('address'),
            'freebie_type_id'   => $request->get('freebie_type'),
            'freebie_value'     => $request->get('free_value'),
            'freebie_issue_date'=> $freebie_date,
            'bar_code'          => $request->get('barcode'),

          ]);
          $inf_type     = InfluencerType::all();
          $places  = Places::all();
          $freebie_types = Freebie::all();
          $ass_names = BusinessAssociate::all();
          return redirect()->route('freebie.issue')->with('save','true');
    }

    public function getFreebieIssues(Request $request)
    {
        $freebie = $request->get('inf');
        $freebie_issues = FreebieIssue::join("freebie","freebie_issue.freebie_type_id","freebie.id")
                                        ->where("associate_name_id",$freebie)
                                        ->selectRaw('freebie.*,freebie_issue.*')
                                        ->orderBy("freebie_issue.created_at","desc")
                                        ->take(5)->get();

        return $freebie_issues;
    }

    public function checkFreebieIssues(Request $request) {
        $freebie_issues = FreebieIssue::where("associate_name_id",$request->ass_id)
                               ->orderBy('freebie_issue_date','DESC')->first();
           $busacc=BusinessAssociate::find($request->ass_id);
           if($busacc->freeby_days== null){
               $freedays=180;
           }
           else{
               $freedays=$busacc->freeby_days;
           }

        $fdate = $freebie_issues->freebie_issue_date;
        $tdate = $request->freebie_date;
        $datetime1 = new DateTime($fdate);
        $datetime2 = new DateTime($tdate);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $issue = 0;
        if($days <=$freedays) {
            $issue = 0;
        }
        else {
            $issue = 1;
        }
        return $issue;
    }





}
