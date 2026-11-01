<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCategory;
use App\PossibleUnit;
use App\Product;
use App\PossibleUnitHistory;
use Auth;  

class PriceController extends Controller
{
    
    public function index(Request $request)
    {   
        $cat_id = null;
        if($request->get('category') != null){
            $cat_id = $request->get('category');
        }

        $category = ProductCategory::orderBy('category_name')->get();
        $products = PossibleUnit::join('products','products.id','=','possible_units.product_id')
                                ->where('possible_units.ref','=','B');
        if($cat_id != null){
            $proids   = Product::where('category_id','=',$cat_id)
                               ->pluck('id');
            if($proids != null){
                $products = $products->whereIn('possible_units.product_id',$proids);
            }
        }
        $products = $products->selectRaw('possible_units.*,products.name')
                             ->orderBy('products.name')
                             ->get();

        return view('price/index',compact('category','products','cat_id'));
    }

    public function priceUpdate(Request $request)
    {
        $posunitids = $request->get('posunitid');
        $price      = $request->get('price');

        for ($i=0; $i < count($posunitids); $i++){
            $update = PossibleUnit::find($posunitids[$i]);
            if($update->price != $price[$i]){
                PossibleUnitHistory::create([
                                              'product_id' => $update->product_id,
                                              'unit_id'    => $update->unit_id,
                                              'price'      => $price[$i]
                                          ]);
            }
            $update->price = $price[$i];
            $update->save();
        }

        return redirect()->route('price.list.index')->with(['update' => true]);
    }

    public function otherUnits(Request $request)
    {
        $product_id = $request->get('id');
        $units      = PossibleUnit::join('uom','uom.id','=','possible_units.unit_id')
                                  ->where('possible_units.ref','=','O')
                                  ->where('possible_units.product_id','=',$product_id)
                                  ->selectRaw('possible_units.*,uom.unit')
                                  ->orderBy('uom.unit')
                                  ->get();
        return $units;
    }

    public function otherUnitsUpdate(Request $request)
    {
        $ids   = $request->get('ids');
        $price = $request->get('price');

        for ($i=0; $i < count($ids); $i++){
            $update = PossibleUnit::find($ids[$i]);
            if($update->price != $price[$i]){
                PossibleUnitHistory::create([
                                              'product_id' => $update->product_id,
                                              'unit_id'    => $update->unit_id,
                                              'price'      => $price[$i]
                                          ]);
            }
            $update->price = $price[$i];
            $update->save();
        }
        return 0;
    }

}