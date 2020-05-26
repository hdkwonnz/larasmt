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
        <div class="col-md-3">
            <div>
                <h4>{{ $product->machine->name }}</h4>
            </div>
        </div>
    </div>
        @php
        $feeders = App\Feeder::with('part')
                    ->where('product_id','=',$product->id)
                    ->orderBy('feeder_number', 'asc')
                    ->get();
        @endphp

        @foreach ($feeders as $feeder)
        <div class="row">
            <div class="col-md-1 w-100">
                {{-- <a href="{{ route('part.refill', $feeder) }}" class="btn btn-primary">{{ $feeder->feeder_number }}</a> --}}
                {{-- <span class="btn btn-primary">{{ $feeder->feeder_number }}</span> --}}
                <span class="display-5"><strong>{{ $feeder->feeder_number }}</strong></span>
            <span class="text-danger">:{{ $feeder->position }}</span>
            </div>
            <div class="col-md-1 text-right">
                {{ $feeder->qty }}
            </div>
            <div class="col-md-3">
                {{ $feeder->part->own_partnumber }}
            </div>
            <div class="col-md-3">
                {{ $feeder->part->vendor_partnumber }}
            </div>
            {{-- <div class="col-md-3">
                {{ $feeder->part->description }}
            </div> --}}
            <div class="col-md-2">
                {{ $feeder->part->value }}
            </div>
        </div>
        @endforeach
    @endforeach
    <span>>>> end of page <<<</span>
</div>

@endsection
