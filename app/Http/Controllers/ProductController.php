<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Product;
use App\ProductCategory;
use App\SubCategory;
use App\Group;
use App\SubGroup;
use App\UnitMeasurement;
use App\PossibleUnit;
use App\Office;
use App\PossibleUnitHistory;
use Auth;

class ProductController extends Controller
{
    public function index()
    {   
        $products  = Product::orderBy('name')->get();
        
        return view('product/index',compact('products'));
    }

    public function create()
    {
        $category    = ProductCategory::orderBy('category_name')->get();
        $uom         = UnitMeasurement::orderBy('unit')->get();
        $product_id  = 0;
        $prd_unit    = null;
        $product     = null;
        $units       = null;
        $subcategory = null;
        $groups      = Group::orderBy('group_name')->get();
        $subgroups   = null;
        $otherunits  = null;

        return view('product/create',compact('category','uom','product_id','prd_unit','product','units','subcategory','groups','subgroups','otherunits'));
    }

    public function insert(Request $request)
    {   
        
        $name = Product::where('name','=',strtoupper(trim($request->get('product'))))->count();
        if($name >0){
            return redirect()->route('product.index')->with(['product_name' => true]);
        }

        $code = Product::where('code','=',strtoupper(trim($request->get('code'))))
                       ->where('code','!=','')
                       ->count();
        if($code >0){
            return redirect()->route('product.index')->with(['code' => true]);
        }

        $status = NULL;
        if($request->get('active') == 'Y'){
            $status = 'Y';
        }

        $insert = Product::create([
                                    'name'            => strtoupper(trim($request->get('product'))),
                                    'code'            => strtoupper(trim($request->get('code'))),
                                    'category_id'     => $request->get('category'),
                                    'sub_category_id' => $request->get('sub'),
                                    'group_id'        => $request->get('group'),
                                    'sub_group_id'    => $request->get('subgroup'),
                                    'is_active'       => $status,
                                ]);

        if($insert){
            $insertPoss = PossibleUnit::create([ 
                                                'product_id' => $insert->id,
                                                'unit_id'    => $request->get('unit'),
                                                'base_value' => '1.0',
                                                'price'      => $request->get('price'),
                                                'ref'        => 'B',
                                              ]); 
            PossibleUnitHistory::create([
                                            'product_id' => $insert->id,
                                            'unit_id'    => $request->get('unit'),
                                            'price'      => $request->get('price')
                                      ]);            
        }

        return redirect()->action('ProductController@otherUnits',['id'=>$insert->id]);
    }

    public function otherUnits($id)
    {
        $product     = Product::find($id); 
        $product_id  = $product->id;
        $prd_unit    = PossibleUnit::join('uom','uom.id','=','possible_units.unit_id')
                                   ->where('possible_units.product_id','=',$product->id)
                                   ->where('possible_units.ref','=','B')
                                   ->selectRaw('possible_units.*,uom.unit')
                                   ->first();
        $category    = ProductCategory::orderBy('category_name')->get();
        $uom         = UnitMeasurement::orderBy('unit')->get();
        $units       = PossibleUnit::where('product_id','=',$product->id)->get(); 
        $subcategory = SubCategory::where('category_id','=',$product->category_id)
                                  ->orderBy('sub_category')->get();
        $groups      = Group::orderBy('group_name')->get();
        $subgroups   = null;
        $group_id    = $product->group_id;
        if ($group_id) {
            $subgroups   = SubGroup::where('group_id',$group_id)->orderBy('subgroup_name')->get();
        }
        $otherunits  = PossibleUnit::where('product_id','=',$product->id)->get();

        return view('product/create',compact('category','uom','product_id','prd_unit','product','units','subcategory','groups','subgroups','otherunits'));
    }

    public function edit($id)
    {
        $product   = Product::find($id);
        $category  = ProductCategory::orderBy('category_name')->get();
        $sub_cat   = SubCategory::where('category_id','=',$product->category_id)->get();
        $groups    = Group::orderBy('group_name')->get();
        $uom       = UnitMeasurement::orderBy('unit')->get();
        $possible  = PossibleUnit::where('product_id','=',$product->id)->get();
        $update    = 0;
        $subgroups = SubGroup::where('group_id','=',$product->group_id)->get();
        $Prodt     = Product::find($id)->is_active;
        $base_unit = PossibleUnit::join('uom','uom.id','=','possible_units.unit_id')
                                   ->where('possible_units.product_id','=',$product->id)
                                   ->where('possible_units.ref','=','B')
                                   ->selectRaw('possible_units.*,uom.unit')
                                   ->first();
                                                           
        return view('product/edit',compact('product','category','uom','sub_cat','groups','possible','update','subgroups','Prodt','base_unit'));
    }

    public function deleteOtherUnit(Request $request)
    {
        PossibleUnit::find($request->get('option'))->delete();
        return;
    }

    public function update(Request $request)
    {   
        $status = NULL;
        if($request->get('active') == 'Y'){
             $status = 'Y';
        }

        $name    = Product::where('name','=',strtoupper(trim($request->get('product'))))
                          ->where('id','!=',$request->get('prd_id'))->count();
        if($name > 0){  
            return redirect()->route('product.index')->with(['product_name' => true]);
        }

        $code    = Product::where('code','=',strtoupper(trim($request->get('code'))))
                          ->where('id','!=',$request->get('prd_id'))
                          ->where('code','!=','')
                          ->count();
        if($code >0){
            return redirect()->route('product.index')->with(['code' => true]);
        }

        $update = Product::where('id','=',$request->get('prd_id'))
                         ->update([
                                    'name'            => strtoupper(trim($request->get('product'))),
                                    'code'            => strtoupper(trim($request->get('code'))),
                                    'category_id'     => $request->get('category'),
                                    'sub_category_id' => $request->get('sub'),
                                    'group_id'        => $request->get('group'),
                                    'sub_group_id'    => $request->get('subgroup'),
                                    'is_active'       => $status,
                                 ]);

        if($update){
            $ex_poss = PossibleUnit::where('product_id','=',$request->get('prd_id'))
                                   ->where('ref','=','B')
                                   ->update([
                                                'unit_id'  => $request->get('unit'),
                                                'price'    => $request->get('price'),
                                          ]);
            $history = PossibleUnitHistory::where('product_id','=',$request->get('prd_id'))
                                          ->where('unit_id','=',$request->get('unit'))
                                          ->first();
            if($history != null){
                if($history->price != $request->get('price')){
                    PossibleUnitHistory::create([
                                                  'product_id' => $request->get('prd_id'),
                                                  'unit_id'    => $request->get('unit'),
                                                  'price'      => $request->get('price')
                                              ]);
                }
            }
            else{
                PossibleUnitHistory::create([
                                              'product_id' => $request->get('prd_id'),
                                              'unit_id'    => $request->get('unit'),
                                              'price'      => $request->get('price')
                                          ]);
            }
        }
          
        return redirect()->route('product.edit',$request->get('prd_id'));
    }

    public function getSubCat(Request $request)
    {
        $sub = SubCategory::where('category_id','=',$request->get('option'))->orderBy('sub_category')->get();
                                                       
        return [$sub];
    }

    public function getSubGroup(Request $request)
    {
        $subgrp = SubGroup::where('group_id','=',$request->get('option'))->get();

        return $subgrp;
    }

    public function insertUnits(Request $request)
    {   
        $prd      = Product::find($request->get('product_id'));
        $possible = PossibleUnit::where('product_id','=',$request->get('product_id'))
                                ->where('ref','=','B')->first();
        if($request->get('unit') != NULL){
            $i = 0;
            foreach ($request->get('unit') as $unit) {
                PossibleUnit::firstOrCreate([
                                                'product_id' => $request->get('product_id'),
                                                'unit_id'    => $unit,
                                          ],[
                                                'base_value' => $request->get('base_value')[$i],
                                                'price'      => $request->get('base_price')[$i],
                                                'ref'        => 'O',
                                          ]);
                $i++;
            }
        }

        return redirect()->route('product.index')->with('message','true');
    }

    public function updateUnits($id,Request $request)
    {   
        if($request->get('unit') != NULL){
            $i = 0;
            foreach ($request->get('unit') as $unit){
                PossibleUnit::firstOrCreate([
                                                'product_id' => $request->get('product_id'),
                                                'unit_id'    => $unit,
                                          ],[
                                                'base_value' => $request->get('base_value')[$i],
                                                'price'      => $request->get('base_price')[$i],
                                                'ref'        => 'O',
                                          ]);
                $i++;
            }
        }
  
        return redirect()->route('product.index')->with('update','true');
    }


}