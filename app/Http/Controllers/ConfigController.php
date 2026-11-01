<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CommonMaster;
use Auth;

class ConfigController extends Controller
{
    
    public function index()
    {   
        $heads   = CommonMaster::where('parent_id','=',0)->orderBy('name')->where('is_active','=','Y')->get();
        $hidden_prnts = CommonMaster::where('is_active','=','N')->pluck('id');
      	$masters = CommonMaster::where('parent_id','!=',0)->whereNotIn('parent_id',$hidden_prnts)->orderBy('parent_id')->get();
    
    	return view('configuration/index',compact('heads','masters'));
    }

    public function store(Request $request)
    {
    	if($request->get('master_id') == NULL)
       	{
          	$master = CommonMaster::where('parent_id','=',$request->get('head'))
                          		  ->where('name','=',strtoupper($request->get('attribute')))
                          		  ->count();

          	if($master > 0)
          	{
          		return redirect('config/index')->with('exist','true'); 
          	}
          
          	$data = CommonMaster::find($request->get('head'))->name;
       	  	CommonMaster::create(array( 
	       	  						  	'name'      => strtoupper($request->get('attribute')),
	       	  	                      	'code' 	    => strtoupper($request->get('attribute')),
	       	  	                      	'parent_id' => $request->get('head'),
	       	  	                      	'seq'       => $request->get('sequence'),
	       	  	                      	'is_active' => ($request->get('is_active') != null)? 'Y' : 'N'
	       	  	                  	));

       	  	return redirect('config/index')->with('save','true');
       	}
       	else
       	{
          	$data = CommonMaster::find($request->get('head'))->name;
       	  	CommonMaster::where('id','=',$request->get('master_id'))
	       	          	->update(array( 
	       	          				  	'name'      => strtoupper($request->get('attribute')),
	       	  	                      	'parent_id' => $request->get('head'),
	       	  	                      	'seq'       => $request->get('sequence'),
	       	  	                      	'is_active' => ($request->get('is_active') != null)? 'Y' : 'N'
	       	  	                   	));

       	  	return redirect('config/index')->with('Update','true');              
       	}
    }

    public function edit(Request $request)
    {
        $master = CommonMaster::where('id','=',$request->get('option'))->get();

        return $master;
    }

    public function delete(Request $request)
    {
      $del = CommonMaster::find($request->get('option'))->delete();
      return 1;
    }

}