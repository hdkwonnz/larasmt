<?php

namespace App\Http\Controllers;

use App\Part;
use App\Feeder;
use App\Machine;
use App\Product;
use App\Shiftwork;
use App\Department;
use App\Productname;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showCreateFeederForm()
    {

        return view('product.showCreateFeederForm');

    }

    public function jsutNames()
    {
        $justProductNames = Productname::all();

        $justMachines = Machine::all();

        $justDepartments = Department::all();

        return response()->json([
            'justProductNames' => $justProductNames,
            'justMachines' => $justMachines,
            'justDepartments' => $justDepartments
        ]);
    }

    public function getFeeders()
    {
        $productId = $_GET['productId'];

        $feeders = Feeder::where('product_id','=',$productId)
                        ->orderBy('feeder_number', 'asc')
                        ->get();
        //return $feeders;

        $parts = [];
        foreach($feeders as $feeder){
            $part = Part::find($feeder->part_id);
            $part->setAttribute('feeder_number',$feeder->feeder_number);//stackoverflow.com/questions/31474452/manually-add-item-to-existing-object-laravel-5
            $part->setAttribute('feeder_position',$feeder->position);
            $part->setAttribute('feeder_id',$feeder->id);
            $parts[] = $part; //add to array
        }
        //return $parts;

        return response()->json([
            'parts' => $parts,
        ]);
    }

    public function checkFeeder(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productNameId' => 'required|numeric',
            'machineId' => 'required|numeric',
            'departmentId'  => 'required|numeric'
        ]);

        $product = Product::
            where('productname_id','=',$request->productNameId)
            ->where('machine_id','=',$request->machineId)
            ->where('department_id','=',$request->departmentId)
            ->first();

        if (!$product){
            return response()->json([
                'errorMsg' => 'sorry! no products at this point.',
            ]);
        }

        $feeders = Feeder::where('product_id','=',$product->id)
                        ->orderBy('feeder_number', 'asc')
                        ->get();

        // $parts = [];
        // foreach($feeders as $feeder){
        //     $part = Part::find($feeder->part_id);
        //     $part->setAttribute('feeder_number',$feeder->feeder_number);//stackoverflow.com/questions/31474452/manually-add-item-to-existing-object-laravel-5
        //     $part->setAttribute('feeder_position',$feeder->position);
        //     $part->setAttribute('feeder_id',$feeder->id);
        //     $parts[] = $part; //add to array
        // }
        // // return $parts;

        return response()->json([
            'errorMsg' => null,
            //'parts' => $parts,
            'product' => $product
        ]);
    }

    public function deleteFeeder(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'feederId' => 'required|numeric',
        ]);

        $feeder = Feeder::find($request->feederId);

        $feeder->delete();

        return response()->json([
            'successMsg' => 'completed to delete feeder list.',
        ]);
    }

    public function createFeeder(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productId' => 'required|numeric',
            'feederNumber' => 'required|numeric',
            'partNumber'  => 'required',
            'feederPosition' => 'required',
        ]);

        //check to exist for part number
        $part = Part::where('own_partnumber','=',$request->partNumber)
                    ->first();
        if (!$part){
            return response()->json([
                'noPartSw' => true
            ]);
        }

        //check to duplicate for feeder number
        $checkFeeder = Feeder::where('product_id','=',$request->productId)
                        ->where('feeder_number','=',$request->feederNumber)
                        ->first();
        if ($checkFeeder){
            return response()->json([
                'errorMsg' => 'sorry! feeder number already exist.',
            ]);
        }

        //create feeder table
        $feeder = Feeder::create([
            'product_id' => $request->productId,
            'part_id' => $part->id,
            'feeder_number' => $request->feederNumber,
            'position' => $request->feederPosition,
            'author' => auth()->user()->name,
        ]);
        if ($feeder){
            return response()->json([
                'successMsg' => 'completed to add feeder list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }

    }

    public function index(Request $request)
    {
        //return $request->all();

        ////$parts = Part::with('feeders')->get();

        /////good example for eager loading(constraining eager loading) below/////
        ////stackoverflow.com/questions/40533776/eager-loading-with-conditional-laravel
        ////stackoverflow.com/questions/45419105/how-to-use-conditions-inside-laravel-eager-loading
        // $parts = Part::with(['feeders' =>
        //     function($query){
        //         $query->where('product_id','=','1');
        //     }
        // ])->get();

        // $parts = Part::whereHas(
        //     'feeders',function($query){
        //         $query->where('product_id','=','2');
        //     }
        // )->get();
        // return $parts;

        $request->validate([
            'productNameId' => 'required|numeric',
            'lineId'  => 'required|numeric',
        ]);

        $productName = Productname::findOrFail($request->productNameId);
        $department = Department::findOrFail($request->lineId);

        $products = Product::with('machine','feeders')
            ->where('productname_id','=',$request->productNameId)
            ->where('department_id','=',$request->lineId)
            ->get();
        //return $products;

        $count = $products->count();

        if ($count == 0){
            return back()->with('error','sorry! no products at this point.');
        }

        return view('product.index', compact('products','productName','department'));
    }

    public function selectProduct()
    {
        $justProductNames = Productname::all();

        $justDepartments = Department::all();

        return view('product.selectProduct', compact('justProductNames','justDepartments'));
    }
}
