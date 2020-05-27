@extends('layouts.app')

@section('content')

<link href="/myCss/wholeReading.css" rel="stylesheet">

<div class="container mt-5">
    <!-- this row will not appear when printing -->
    <!-- printme function is defined on resources/js/app.js-->
    <div class="row mt-3 no-print">
        <div class="col-xs-12">
          <a href="#" @click.prevent="printme" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <div class="row bg-success pt-2 top_menu">
        <div class="col-md-3">
            <h2 class="">
                {{ $productName->name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="">
                {{ $department->name }}
            </h2>
        </div>
    </div>

    @foreach ($products as $product)
    <div class="row mt-3">
        <div class="col-md-5">
            <div>
                <h3 class="bg-warning">{{ $product->machine->name }}</h3>
            </div>
        </div>
    </div>
    @php
    $feeders = App\Feeder::with('part')
                ->where('product_id','=',$product->id)
                ->orderBy('feeder_number', 'asc')
                ->get();
    @endphp

    <!-- table -->
    <div class="row">
        <div class="table-responsive mt-1">
            <table class="table table-striped table-bordered table-sm">
                <tbody>
                    @foreach ($feeders as $feeder)
                    <tr style="width: 15%">
                        <td>
                            {{ $feeder->feeder_number }}
                            <span class="text-danger">{{ $feeder->position }}</span>
                        </td>
                        <td style="width: 10%" class="text-right">
                            {{ $feeder->qty }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->part->own_partnumber }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->part->vendor_partnumber }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->part->value }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!-- end of table -->
    @endforeach

    <span>>>> end of page <<<</span>
</div>

@endsection
