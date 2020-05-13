<?php

namespace App\Http\Controllers;

use App\Feeder;
use App\Machine;
use App\Product;
use App\Shiftwork;
use App\Department;
use App\Productname;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    public function showMachineForm()
    {
        return view('create.showMachineForm');
    }

    public function showDepartmentForm()
    {
        return view('create.showDepartmentForm');
    }

    public function showShiftForm()
    {
        return view('create.showShiftForm');
    }

    public function showProductnameForm()
    {
        return view('create.showProductnameForm');
    }

    public function showProductForm()
    {
        return view('create.showProductForm');
    }

    public function getMachines()
    {
        $machines = Machine::orderBy('created_at','desc')
                        ->get();

        return response()->json([
            'machines' => $machines,
        ]);
    }

    public function getDepartments()
    {
        $departments = Department::orderBy('created_at','desc')
                        ->get();

        return response()->json([
            'departments' => $departments,
        ]);
    }

    public function getShifts()
    {
        $shifts = Shiftwork::orderBy('created_at','desc')
                    ->get();

        return response()->json([
            'shifts' => $shifts,
        ]);
    }

    public function getProductnames()
    {
        $productnames = Productname::orderBy('created_at','desc')
                            ->get();

        return response()->json([
            'productnames' => $productnames,
        ]);
    }

    public function getProducts()
    {
        $products = Product::with('productname','machine','department')
                        ->orderBy('created_at','desc')
                        ->get();
        //return $products;

        return response()->json([
            'products' => $products,
        ]);
    }

    public function deleteMachine(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'machineId' => 'required|numeric',
        ]);

        $products = Product::where('machine_id','=',$request->machineId)
                        ->count();
        if ($products > 0){
            return response()->json([
                'errorMsg' => 'this machine has child product.',
            ]);
        }

        $machine = Machine::Find($request->machineId);

        $machine->delete();

        return response()->json([
            'successMsg' => 'completed to delete machine list.',
        ]);
    }

    public function deleteDepartment(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'departmentId' => 'required|numeric',
        ]);

        $products = Product::where('department_id','=',$request->departmentId)
                        ->count();
        if ($products > 0){
            return response()->json([
                'errorMsg' => 'this department has child product.',
            ]);
        }

        $department = Department::Find($request->departmentId);

        $department->delete();

        return response()->json([
            'successMsg' => 'completed to delete department list.',
        ]);
    }

    public function deleteShift(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'shiftId' => 'required|numeric',
        ]);

        $shift = Shiftwork::Find($request->shiftId);

        $shift->delete();

        return response()->json([
            'successMsg' => 'completed to delete shift list.',
        ]);
    }

    public function deleteProductname(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productnameId' => 'required|numeric',
        ]);

        $products = Product::where('productname_id','=',$request->productnameId)
                        ->count();
        if ($products > 0){
            return response()->json([
                'errorMsg' => 'this productname has child product.',
            ]);
        }

        $productname = Productname::Find($request->productnameId);

        $productname->delete();

        return response()->json([
            'successMsg' => 'completed to delete product name list.',
        ]);
    }

    public function deleteProduct(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productId' => 'required|numeric',
        ]);

        $feeders = Feeder::where('product_id','=',$request->productId)
                        ->count();
        if ($feeders > 0){
            return response()->json([
                'errorMsg' => 'this product has child feeder.',
            ]);
        }

        $product = Product::Find($request->productId);

        $product->delete();

        return response()->json([
            'successMsg' => 'completed to delete product list.',
        ]);
    }

    public function createMachine(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'machineName' => 'required',
        ]);

        //create machine table
        $machine = Machine::create([
            'name' => $request->machineName,
        ]);

        if ($machine){
            return response()->json([
                'successMsg' => 'completed to add machine list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }

    public function createDepartment(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'departmentName' => 'required',
        ]);

        //create department table
        $department = Department::create([
            'name' => $request->departmentName,
        ]);

        if ($department){
            return response()->json([
                'successMsg' => 'completed to add department list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }

    public function createShift(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'shiftName' => 'required',
        ]);

        //create shift table
        $shift = Shiftwork::create([
            'name' => $request->shiftName,
        ]);

        if ($shift){
            return response()->json([
                'successMsg' => 'completed to add shift list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }

    public function createProductname(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productName' => 'required',
        ]);

        //create productname table
        $productname = Productname::create([
            'name' => $request->productName,
        ]);

        if ($productname){
            return response()->json([
                'successMsg' => 'completed to add product name list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }

    public function createProduct(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'productNameId' => 'required|numeric',
            'machineId' => 'required|numeric',
            'departmentId' => 'required|numeric',
        ]);

        //create productname table
        $product = Product::create([
            'productname_id' => $request->productNameId,
            'machine_id' => $request->machineId,
            'department_id' => $request->departmentId,
        ]);

        if ($product){
            return response()->json([
                'successMsg' => 'completed to add product list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }
}
