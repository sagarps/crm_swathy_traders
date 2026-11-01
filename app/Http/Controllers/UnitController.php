<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnitMeasurement;
use Auth;

class UnitController extends Controller
{
    
    public function index()
    {   
        $units = UnitMeasurement::orderBy('unit')->get();
        
        return view('unit/index',compact('units'));
    }

    public function create()
    {
        return view('unit/create');
    }

    public function store(Request $request)
    {                  
        $unit = UnitMeasurement::where('unit',strtoupper($request->get('name')))->first(); 
        if(!is_null($unit)){
            return Redirect('unit/create')->with('exist','true');
        }

        UnitMeasurement::create([ 'unit'   => strtoupper($request->get('name'))]); 

        return redirect()->route('unit.index')->with('save','true');
    }

    public function edit($id,Request $request)
    { 
        $unit    = UnitMeasurement::find($id);

        return view('unit/edit',compact('unit'));
    }

    public function update(Request $request)
    {  
        $unit = UnitMeasurement::where('unit',strtoupper($request->get('name')))
                               ->where('id','!=',$request->get('unit'))->first(); 

        if(!is_null($unit)){
            return redirect()->route('unit.edit',$request->get('unit'))->with('exist','true');
        }

        UnitMeasurement::where('id','=',$request->get('unit'))
                       ->update([ 'unit'  => strtoupper($request->get('name')) ]);
        

        return redirect()->route('unit.index')->with('update','true');
    }


}