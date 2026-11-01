<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeamMapping;
use App\EmployeeDetail;
use Auth;

class TeamMappingController extends Controller
{
    
    public function index()
    {   
        $team      = TeamMapping::join('employee_details','employee_details.id','=','team_mapping.parent_id')
                                ->where('team_mapping.office_id','=',Auth::user()->office_id)  
                                ->distinct('employee_details.name,team_mapping.parent_id')
                                ->selectRaw('employee_details.name,team_mapping.parent_id')
                                ->orderBy('employee_details.name')
                                ->get();
        $parent_id = TeamMapping::where('office_id','=',Auth::user()->office_id)
                                ->pluck('parent_id');
        $employee  = EmployeeDetail::whereNotIn('id',$parent_id)
                                   ->where('is_active','=','Y')
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->orderBy('name')
                                   ->get();

        return view('team_mapping/index',compact('team','employee'));
    }

    public function store(Request $request)
    {
        $lead   = $request->get('lead');
        $member = $request->get('member');

        if(count($member) > 0){
           foreach ($member as $mem) {
                TeamMapping::firstOrCreate([
                                              'parent_id'   => $lead,
                                              'employee_id' => $mem,
                                              'office_id'   => Auth::user()->office_id
                                          ]);
            } 
        }

        return redirect()->route('team.mapping.index')->with('save','true');
    }
    
    public function memberList(Request $request)
    {
        $lead      = $request->get('val');
        $member_id = TeamMapping::where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $parent_id = TeamMapping::where('office_id','=',Auth::user()->office_id)
                                ->pluck('parent_id');
        $members   = EmployeeDetail::whereNotIn('id',$member_id)/*
                                   ->whereNotIn('id',$parent_id)*/
                                   ->where('id','!=',$lead)
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->where('is_active','=','Y')
                                   ->orderBy('name')
                                   ->get();
        return $members;
    }

    public function parentList(Request $request)
    {
        $parent    = $request->get('parent_id');
        $parent_id = TeamMapping::where('parent_id','!=',$parent)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('parent_id');
        $employee  = EmployeeDetail::whereNotIn('id',$parent_id)
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->where('is_active','=','Y')
                                   ->orderBy('name')
                                   ->get();

        $member_id = TeamMapping::whereIn('parent_id',$parent_id)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $members   = EmployeeDetail::/*whereNotIn('id',$parent_id)
                                   ->*/where('id','!=',$parent)
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->whereNotIn('id',$member_id)
                                   ->where('is_active','=','Y')
                                   ->orderBy('name')
                                   ->get();

        $lead_mem  = TeamMapping::where('parent_id','=',$parent)
                                ->where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');

        return [$employee,$members,$lead_mem,$member_id,$parent_id,$parent];

    }

    public function editMemberList(Request $request)
    {
        $lead      = $request->get('val');
        $member_id = TeamMapping::where('office_id','=',Auth::user()->office_id)
                                ->pluck('employee_id');
        $parent_id = TeamMapping::where('office_id','=',Auth::user()->office_id)
                                ->pluck('parent_id');
        $members   = EmployeeDetail::whereNotIn('id',$member_id)/*
                                   ->whereNotIn('id',$parent_id)  */  
                                   ->where('id','!=',$lead)
                                   ->where('office_id','=',Auth::user()->office_id)
                                   ->where('is_active','=','Y')
                                   ->orderBy('name')
                                   ->get();
        return $members;
    }

    public function update(Request $request)
    {   

        $lead   = $request->get('edit_lead');
        $member = $request->get('edit_member');
        if($request->get('edit_id') != $lead){
            TeamMapping::where('parent_id','=',$request->get('edit_id'))
                       ->where('office_id','=',Auth::user()->office_id)
                       ->delete();
        }

        if(count($member) > 0){
           foreach ($member as $mem) {
                TeamMapping::firstOrCreate([
                                              'parent_id'   => $lead,
                                              'employee_id' => $mem,
                                              'office_id'   => Auth::user()->office_id
                                          ]);
            } 
            TeamMapping::where('parent_id','=',$lead)
                       ->whereNotIn('employee_id',$member)
                       ->where('office_id','=',Auth::user()->office_id)
                       ->delete();
        }

        return redirect()->route('team.mapping.index')->with('update','true');
    }

    public function deleteMemberList(Request $request)
    {
        $lead      = $request->get('val');
        TeamMapping::where('parent_id','=',$lead)
                   ->where('office_id','=',Auth::user()->office_id)
                   ->delete();

        return 0;
    }
}   