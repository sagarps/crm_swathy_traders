<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCategory;
use App\SubCategory;
use App\Group;
use App\SubGroup;
use Auth;

class ProductCategoryController extends Controller
{    
    public function index()
    {
        $categories = ProductCategory::orderBy('category_name')->get();
        $sub_cat    = SubCategory::orderBy('sub_category')->get();
        $groups     = Group::orderBy('group_name')->get();
        $sub_groups = SubGroup::orderBy('subgroup_name')->get();

        return view('category/index',compact('categories','sub_cat','groups','sub_groups'));
    }

    public function store(Request $request)
    {  
        $category = ProductCategory::where('category_name','=',$request->get('category'))->count();

        if($category > 0)
        {
          return redirect('product/category/index')->with('exist','true'); 
        }
         
        ProductCategory::create(array(
                                        'category_name' => strtoupper($request->get('category')),
                                   ));
          
        return redirect('product/category/index')->with('save','true');
    }

    public function edit(Request $request)
    {
        $category = ProductCategory::find($request->get('option'));

        return  $category;
    }

    public function update(Request $request)
    {   
        $count = ProductCategory::where('category_name','=',strtoupper($request->get('category')) )
                                ->where('id','!=',$request->get('category_id'))->count();
        if($count > 0){
          return redirect('product/category/index')->with('exist','true'); 
        }
        ProductCategory::where('id','=',$request->get('category_id'))
                       ->update(array(
                                      'category_name' => strtoupper($request->get('category')),
                                    ));                                

        return redirect('product/category/index')->with('Update','true');
    }

    public function storeSub(Request $request)
    {
        $sub = SubCategory::where('category_id','=',$request->get('cat_id'))
                          ->where('sub_category','=',strtoupper($request->get('sub_category')))->count();

        if($sub > 0)
        { 
          return redirect('product/category/index')->with('exist','true'); 
        }
         
        SubCategory::create(array( 
                                     'sub_category' => strtoupper($request->get('sub_category')),
                                     'category_id' => $request->get('cat_id') 
                                ));

        return redirect('product/category/index')->with('save','true'); 
    }

    public function editSub(Request $request)
    {
        $sub_category = SubCategory::find($request->get('option'));

        return $sub_category;
    }

    public function updateSub(Request $request)
    { 
        $count = SubCategory::where('id','!=',$request->get('sub_id'))
                            ->where('category_id','=',$request->get('cat_id'))
                            ->where('sub_category','=',strtoupper( $request->get('sub_category') ))->count();
        if ($count > 0) {
          return redirect('product/category/index')->with('exist','true'); 
        }

        SubCategory::where('id','=',$request->get('sub_id'))
                   ->update([
                              'sub_category' => strtoupper( $request->get('sub_category') ),
                              'category_id'  => $request->get('cat_id')
                          ]);

        return redirect('product/category/index')->with('Update','true'); 
    }

    public function storeGroup(Request $request)
    {
        $group = Group::where('group_name','=',strtoupper($request->get('group')))->count();

        if($group > 0)
        { 
            return redirect('product/category/index')->with('exist','true'); 
        }
         
        Group::create(array(  
                              'group_name' => strtoupper($request->get('group')),
                         ));

        return redirect('product/category/index')->with('save','true'); 
    }

    public function editGroup(Request $request)
    {
        $group = Group::find($request->get('option'));
        $data  = array($group);
        
        return $data;
    }
    
    public function updateGroup(Request $request)
    {
        $group = Group::where('id','!=',$request->get('group_id'))
                      ->where('group_name','=',strtoupper($request->get('group')))->count();

        if($group > 0)
        { 
            return redirect('product/category/index')->with('exist','true'); 
        }

        Group::where('id','=',$request->get('group_id'))
             ->update([
                          'group_name' => strtoupper($request->get('group')),
                    ]);
         
        return redirect('product/category/index')->with('update','true'); 
    }

    public function storeSubGroup(Request $request)
    {
        $subgroup = SubGroup::where('group_id','=',$request->get('grp_id'))
                            ->where('subgroup_name','=',strtoupper($request->get('sub_group')))->count();

        if($subgroup > 0)
        { 
            return redirect('product/category/index')->with('exist','true'); 
        }
         
        SubGroup::create(array(  
                                'subgroup_name' => strtoupper($request->get('sub_group')),
                                'group_id' => $request->get('grp_id') 
                            ));

        return redirect('product/category/index')->with('save','true'); 
    }

    public function editSubgrp(Request $request)
    { 
        $subgroup = SubGroup::join('groups','groups.id','=','sub_groups.group_id')
                            ->where('sub_groups.id','=',$request->get('option'))
                            ->select('sub_groups.subgroup_name','sub_groups.id','sub_groups.group_id','groups.group_name')
                            ->first();

        $groups   = Group::where('id','!=',$subgroup->group_id)
                         ->orderBy('group_name')->get();

        $data     = array($subgroup,$groups);

        return $data;
    }

    public function updateSubGroup(Request $request)
    {
        $subgroup = SubGroup::where('id','!=',$request->get('subgroup_id'))
                            ->where('group_id','=',$request->get('grp_id'))
                            ->where('subgroup_name','=',strtoupper($request->get('sub_group')))->count();

        if($subgroup > 0)
        { 
            return redirect('product/category/index')->with('exist','true'); 
        }

        SubGroup::where('id','=',$request->get('subgroup_id'))
                ->update([
                           'subgroup_name' => strtoupper($request->get('sub_group')),
                           'group_id'      => $request->get('grp_id')
                       ]);
         
        return redirect('product/category/index')->with('save','true'); 
    }
}
