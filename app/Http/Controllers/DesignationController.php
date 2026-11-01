<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use Auth;

class DesignationController extends Controller
{
    
    public function index()
    {   
        $designation = Designation::orderBy('designation')->get();
        
        return view('designation/index',compact('designation'));
    }

    public function store(Request $request)
    {
        $des = Designation::where('designation','=',strtoupper($request->get('designation')))->count();

        if($des > 0){
            return redirect()->route('designation.index')->with('exist','true');
        }

        Designation::create([
                             'designation' => strtoupper($request->get('designation'))
                          ]);

        return redirect()->route('designation.index')->with('save','true');
    }

    public function update(Request $request)
    {
        $id = $request->get('edit_id');
        $des = Designation::where('id','!=',$id)
                          ->where('designation','=',strtoupper($request->get('designation_edit')))->count();

        if($des > 0){
            return redirect()->route('designation.index')->with('exist','true');
        }
        
        Designation::where('id','=',$id) 
                   ->update([
                             'designation' => strtoupper($request->get('designation_edit'))
                          ]);     

        return redirect()->route('designation.index')->with('update','true');         
    }
}