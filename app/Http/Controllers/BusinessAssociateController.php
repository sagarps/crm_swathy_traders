<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FunctionType;
use App\InfluencerType;
use Auth;
use App\UserRole;
use App\Places;
use App\BusinessAssociate;
use App\Freebie;
use App\EmployeeDetail;

class BusinessAssociateController extends Controller
{
    public function addBusinessAssociate(Request $request)
    {
        $places  = Places::all();
        $inf_type     = InfluencerType::all();

        return view('business_associate/index',compact('places','inf_type'));

    }

    public function addAssociate(Request $request)
    {
        $nassociate = $request->get('ass');
        $count = InfluencerType::where('influencer_type',$nassociate)->count();
        $data = null;
        if($count > 0) {
            $data  = 0 ;
        }
        else {
            $data = 1;
            $asst = new InfluencerType;
            $asst->influencer_type = strtoupper($nassociate);
            $asst->save();
        }
        return $asst->id;
    }

    public function createAssociate(Request $request)
    {
        $associate = BusinessAssociate::create([
            'associate_type_id'  => $request->get('inf_type'),
            'associate_name'     => strtoupper($request->get('associate')),
            'contact_person'     => strtoupper($request->get('cont_person')),
            'contact_no'         => $request->get('cont_no'),
            'place_id'           => strtoupper($request->get('place')),
            'address'            => $request->get('address'),
            'office_id'          => Auth::user()->office_id,
            'freeby_days'          => $request->get('freeby'),

          ]);

          return redirect()->route('associate.index')->with('save','true');
    }

    public function showAssociates(Request $request)
    {
        $associate_type = $request->get('inf');
        $associate_names = BusinessAssociate::where('associate_type_id',$associate_type)->get();
        return $associate_names;
    }

    public function searchAssociates(Request $request)
    {
        $associates = BusinessAssociate::where('associate_name','like','%'.$request->get('term').'%')
                        ->orWhere('contact_no','like','%'.$request->get('term').'%')
                        ->selectRaw('associate_name,contact_no,id')->orderBy('associate_name')
                        ->get();

        return $associates;
    }

    public function showAssociateDetails(Request $request)
    {
        if( strpos( $request->get('value'), '/' ) !== false ){
            $info     = explode( '/', $request->get('value') );
            // echo $info[0]."--".$info[1];
            $associate = BusinessAssociate::where('associate_name','=',$info[0])
                                ->where('contact_no','=',$info[1])
                                ->first();
            $type_id  = $associate->associate_type_id;
            $type = InfluencerType::find($type_id)->id;
            // $type = $associate->associate_type->ifluencer_type;
            // return $type;
            // return $info;
            // echo $customer;
            // $place =
            // $customer['place'] = $place;
            // dd($associate);
            $associate['type']  = $type;
            return $associate;
        }
    }
    public function getBusinessAssociates(Request $request)
    {
        $associates = BusinessAssociate::where('office_id',Auth::user()->office_id)->get();
        return view('business_associate/index',compact('associates'));

    }
    public function createBusinessAssociate(Request $request)
    {
        $places  = Places::all();
        $inf_type     = InfluencerType::all();
        return view('business_associate/create',compact('places','inf_type'));

    }

    public function editBusinessAssociate($id) {
        $associate   = BusinessAssociate::find($id);
        $places       = Places::all();
        $inf_type     = InfluencerType::all();
        return view('business_associate/edit',compact('inf_type','places','associate'));

    }

    public function updateBusinessAssociate(Request $request) {
        $associate    = BusinessAssociate::find($request->get('edit_id'));
        $associate->associate_type_id =$request->get('inf_type');
        $associate->associate_name         = strtoupper($request->get('associate'));
        $associate->contact_person       = strtoupper($request->get('cont_person'));
        $associate->contact_no           = $request->get('cont_no');
        $associate->place_id         = strtoupper($request->get('place'));
        $associate->address          = $request->get('address');
        $associate->freeby_days          = $request->get('freeby');
        $associate->save();
        return redirect()->route('associate.index')->with('update','true');


    }

}
