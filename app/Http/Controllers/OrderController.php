<?php

namespace App\Http\Controllers;

use App\Part;
use App\Order;
use App\Product;
use App\Shiftwork;
use App\Department;
use App\Feeder;
use App\Orderfeeder;
use App\Productname;
use App\Ordermachine;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'orderId' => 'required|numeric',
        ]);

        $order = Order::find($request->orderId);

        if(!$order){
            return view('error.somethingWrong');
        }

        $orderMerchines = Ordermachine::where('order_number','=',$order->order_number)
            ->get();

        return view('order.details',compact('order','orderMerchines'));
    }

    public function getOrders()
    {
        $orders = Order::orderBy('updated_at','desc')
                    ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function askShowOrders()
    {
        $orders = Order::where('status','=','active')
            ->get();

        return view('order.askShowOrder',compact('orders'));
    }

    public function showForm()
    {
        // $this->authorize('isAdminOrEditor');

        return view('order.showForm');
    }

    public function orderJustNames()
    {
        // $this->authorize('isAdminOrEditor');

        $justProductNames = Productname::orderBy('name','asc')->get();
        $justDepartments = Department::orderBy('name','asc')->get();
        $justShifts = Shiftwork::orderBy('name','asc')->get();

        return response()->json([
            'justProductNames' => $justProductNames,
            'justShifts' => $justShifts,
            'justDepartments' => $justDepartments
        ]);
    }

    public function makeOrder(Request $request)
    {
        // $this->authorize('isAdmin');////for ACL

         //return $request->all();

        //check validation
        $request->validate([
            'productNameId' => 'required|numeric',
            'shiftId' => 'required|numeric',
            'departmentId' => 'required|numeric',
            'orderNumber' => 'required|',
        ]);

        $checkOrder = Order::where('order_number','=',$request->orderNumber)
                        ->first();
        if ($checkOrder){
            return response()->json([
                'errorMsg' => 'The order has already been taken.',
            ]);
        }

        $productName = Productname::find($request->productNameId);
        $department = Department::find($request->departmentId);
        $shift = Shiftwork::find($request->shiftId);

        $products = Product::with('machine','feeders')
            ->where('productname_id','=',$request->productNameId)
            ->where('department_id','=',$request->departmentId)
            ->get();

        $count = $products->count();

        if ($count == 0){
            return response()->json([
                'errorMsg' => 'sorry! no products at this point.',
            ]);
        }

        $feederCount = 0;
        foreach ($products as $product){
            $fCount = $product->feeders->count();
            $feederCount = $feederCount + $fCount;
        }

        if ($feederCount == 0){
            return response()->json([
                'errorMsg' => 'sorry! no feeder list exist.',
            ]);
        }

        DB::beginTransaction();

        try{
            $order = Order::create([
                'order_number' => $request->orderNumber,
                'productname_id' => $request->productNameId,
                'product_name' => $productName->name,
                'department_id' => $request->departmentId,
                'department_name' => $department->name,
                'shiftwork_id' => $request->shiftId,
                'shift_name' => $shift->name,
                'user_id' => auth()->user()->id,
                'author' => auth()->user()->name,
                'status' => 'waiting'
            ]);
            foreach($products as $product) {
                $orderMachine = Ordermachine::create([
                    'order_id' => $order->id,
                    'order_number' => $request->orderNumber,
                    'machine_id' => $product->machine->id,
                    'machine_name' => $product->machine->name
                ]);
                $feeders = Feeder::with('part')
                        ->where('product_id','=',$product->id)
                        ->orderBy('feeder_number', 'asc')
                        ->get();
                foreach($feeders as $feeder){
                    $orderfeeder = Orderfeeder::create([
                        'order_id' => $order->id,
                        'order_number' => $request->orderNumber,
                        'machine_id' => $product->machine->id,
                        'machine_name' => $product->machine->name,
                        'product_name' => $order->product_name,
                        'department_name' => $order->department_name,
                        'shift_name' => $order->shift_name,
                        'part_id' => $feeder->part_id,
                        'own_partnumber' => $feeder->part->own_partnumber,
                        'vendor_partnumber' => $feeder->part->vendor_partnumber,
                        'description' => $feeder->part->description,
                        'value' => $feeder->part->value,
                        'feeder_number'=>$feeder->feeder_number,
                        'position' => $feeder->position,
                        'author' => auth()->user()->name,
                    ]);
                }
            }

            DB::commit();
        }
        catch(Exception $e){

            DB::rollback();

            return response()->json([
                'errorMsg' => 'woops! something went to wrong.',
            ]);
        }

        return response()->json([
            'successMsg' => 'completed to add order list.',
        ]);
    }

    public function deleteOrder(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'orderId' => 'required|numeric',
        ]);

        $order = Order::Find($request->orderId);

        if ($order->user_id != auth()->user()->id){
            return response()->json([
                'errorMsg' => 'This user is not same as original author.',
            ]);
        }

        if ($order->status == 'active'){
            return response()->json([
                'errorMsg' => 'This order is still active.',
            ]);
        }

        DB::beginTransaction();

        try{

            $order->delete();

            Ordermachine::where('order_id','=', $request->orderId)
                        ->delete();

            Orderfeeder::where('order_id','=', $request->orderId)
                        ->delete();

            Transaction::where('order_id','=', $request->orderId)
                        ->delete();

            DB::commit();
        }
        catch(Exception $e){

            DB::rollback();

            return response()->json([
                'errorMsg' => 'woops! something went to wrong.',
            ]);
        }

        return response()->json([
            'successMsg' => 'completed to delete order list.',
        ]);
    }

    public function editOrder(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'orderId' => 'required|numeric',
            'orderStatus' => 'required',
            'departmentId' => 'required|numeric',
        ]);

        if ($request->orderStatus == 'active')
        {
            $order = Order::where('department_id','=',$request->departmentId)
                    ->where('status','=','active')
                    ->first();
            if ($order){
                return response()->json([
                    'modalError' => 'active order is running at the moment.',
                ]);
            }
        }

        $order = Order::Find($request->orderId);
        $order->status = $request->orderStatus;

        $order->save();

        return response()->json([
            'modalMessage' => 'completed to edit order list.',
        ]);
    }
}
