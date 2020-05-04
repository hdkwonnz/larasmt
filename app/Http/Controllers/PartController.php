<?php

namespace App\Http\Controllers;

use App\Part;
use App\Order;
use App\Feeder;
use App\Product;
use App\Orderfeeder;
use App\Transaction;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function createPartForm()
    {
        return view('part.createPartForm');
    }

    public function getParts()
    {
        $parts = Part::orderBy('created_at','desc')
                    ->take(20)
                    ->get();
        //return $parts;

        return response()->json([
            'parts' => $parts,
        ]);
    }

    public function deletePart(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'partId' => 'required|numeric',
        ]);

        $feeders = Feeder::where('part_id','=',$request->partId)
                        ->count();
        if ($feeders > 0){
            return response()->json([
                'errorMsg' => 'this part has child feeder.',
            ]);
        }

        $part = Part::Find($request->partId);

        $part->delete();

        return response()->json([
            'successMsg' => 'completed to delete part.',
        ]);
    }

    public function createPart(Request $request)
    {
        //return $request->all();

        //check validation
        $request->validate([
            'ownPartNumber' => 'required',
            'vendorPartNumber' => 'required',
            'partValue'  => 'required',
            'partDescription' => 'required',
        ]);

        $part = Part::where('own_partnumber','=',$request->ownPartNumber)
                        ->first();
        if ($part){
            return response()->json([
                'errorMsg' => 'Own Part Number already exist.',
            ]);
        }

        $part = Part::where('vendor_partnumber ','=',$request->vendorPartNumber)
                        ->first();
        if ($part){
            return response()->json([
                'errorMsg' => 'Vendor Part Number already exist.',
            ]);
        }

        //create part table
        $part = Part::create([
            'own_partnumber' => $request->ownPartNumber,
            'vendor_partnumber' => $request->vendorPartNumber,
            'description' => $request->partDescription,
            'value' => $request->partValue,
        ]);

        if ($part){
            return response()->json([
                'successMsg' => 'completed to add parts list.',
            ]);
        }else{
            return response()->json([
                'errorMsg' => 'woops...something wrong.',
            ]);
        }
    }

    public function selectLogsForm()
    {
        $orders = Order::where('status','=','active')
            ->get();

        $count = $orders->count();

        if ($count == 0){
            return back()->with('error','sorry! no orders at this point.');
        }

        return view('part.selectLogsForm',compact('orders'));
    }

    public function showLogs(Request $request)
    {
        //return $request->all();
        $request->validate([
            'orderId' => 'required|numeric',
        ]);

        $order = Order::find($request->orderId);

        if(!$order){
            return view('error.somethingWrong');
        }

        $transactions = Transaction::where('order_number','=',$order->order_number)
                            ->orderBy('created_at','desc')
                            ->get();
        //return $transaction;

        return view('part.showLogs',compact('order','transactions'));
    }

    public function wholeReading()
    {
        if ((isset($_GET['orderNumber'])) && !(isset($_GET['orderId']))){
            $orderNumber = $_GET['orderNumber'];
            $orderFeeder = Orderfeeder::where('order_number', '=',$orderNumber)
                            ->first();
            if (!$orderFeeder){
                return back()->with('error','sorry! no order-feeder list at this point.');
            }
        }

        if ((isset($_GET['orderId'])) && (isset($_GET['orderNumber']))){
            $orderId = $_GET['orderId'];
            $orderNumber = $_GET['orderNumber'];
            $orderFeeder = Orderfeeder::where('id', '=',$orderId)
                            ->where('order_number', '=',$orderNumber)
                            ->first();
            if (!$orderFeeder){
                return back()->with('error','sorry! no order-feeder list at this point.');
            }
        }

        $previous = Orderfeeder::where('id', '<', $orderFeeder->id)
                        ->where('order_number', '=',$orderNumber)
                        ->orderBy('id','desc')->first();

        $next = Orderfeeder::where('id', '>', $orderFeeder->id)
                    ->where('order_number', '=',$orderNumber)
                    ->orderBy('id')->first();

        return view('part.wholeReading',compact('orderFeeder','previous','next'));
    }

    public function showReadingForm()
    {
        //return date('d/m/Y H:i:s');

        $orders = Order::where('status','=','active')
            ->get();

        return view('part.showReadingForm',compact('orders'));
    }

    public function showRefill($feederId)
    {
        $feeder = Feeder::find($feederId);

        $part = Part::find($feeder->part_id);

        $productId = $feeder->product_id;

        $product = Product::with('machine','department')
            ->where('id','=',$productId)
            ->first();

        // return $product;
        // return $feeder;
        return view('part.refill',compact('feeder','part','product'));
    }

    public function updateRefill(Request $request)
    {
        // return $request->all();

        //check validation
        $request->validate([
        'feederId' => 'required|numeric',
        'partNumber' => 'required',
        'scannedType'  => 'required',
        ]);

        // $feeder = Feeder::find($request->feederId);

        // $part = Part::find($feeder->part_id);

        // $originalPartNumber = $part->own_partnumber;

        $orderFeeder = Orderfeeder::find($request->feederId);

        if ($orderFeeder->own_partnumber == $request->partNumber){
            $scannedResult = 'passed';
        }else{
            $scannedResult = 'failed';
        }

        if (($request->scannedType == 'whole') && ($scannedResult == 'failed')){
            return "bad";
        }

        $transaction = Transaction::create([
            'order_id' => $orderFeeder->order_id,
            'scanned_result' => $scannedResult,
            'scanned_type' => $request->scannedType,
            // 'scanned_time' => \Carbon\Carbon::now()->format('H:i:s'),//24 hour format,////???
            'scanned_time' => date('H:i:s'),//24 hour format,////???
            'order_number' => $orderFeeder->order_number,
            'machine_id' => $orderFeeder->machine_id,
            'machine_name' => $orderFeeder->machine_name,
            'product_name' => $orderFeeder->product_name,
            'department_name' => $orderFeeder->department_name,
            'shift_name' => $orderFeeder->shift_name,
            'part_id' => $orderFeeder->part_id,
            'own_partnumber' => $orderFeeder->own_partnumber,
            'vendor_partnumber' => $orderFeeder->vendor_partnumber,
            'value' => $orderFeeder->value,
            'feeder_number'=>$orderFeeder->feeder_number,
            'position' => $orderFeeder->position,
            'scanned_own_partnumber' => $request->partNumber,
            'scanned_vendor_partnumber' => "",
            'scanned_value' => "",
            'user_id' => auth()->user()->id,
            'author' => auth()->user()->name
        ]);

        if ($scannedResult == 'passed'){
            return "good";
        }else{
            return "bad";
        }
    }
}
