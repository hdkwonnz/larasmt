@extends('layouts.app')

@section('content')

{{-- <link href="/myCss/wholeReading.css" rel="stylesheet"> --}}

<div class="container mt-5">

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

        @foreach ($product->feeders as $feeder)
        <div class="row">
            @php
            $partId = $feeder->part_id;
            $part = App\Part::where('id', '=', $partId)
                ->first();
            @endphp
            <div class="col-md-1 w-100">
                {{-- <a href="{{ route('part.refill', $feeder) }}" class="btn btn-primary">{{ $feeder->feeder_number }}</a> --}}
                {{-- <span class="btn btn-primary">{{ $feeder->feeder_number }}</span> --}}
                <span class="display-5"><strong>{{ $feeder->feeder_number }}</strong></span>
            <span class="text-danger">:{{ $part->position }}</span>
            </div>
            <div class="col-md-3">
                {{ $part->own_partnumber }}
            </div>
            <div class="col-md-3">
                {{ $part->vendor_partnumber }}
            </div>
            <div class="col-md-3">
                {{ $part->description }}
            </div>
            <div class="col-md-2">
                {{ $part->value }}
            </div>
        </div>
        @endforeach
    @endforeach

    <!-- this row will not appear when printing -->
    <div class="row mt-3 no-print">
        <div class="col-xs-12">
          <a href="#" @click.prevent="printme" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
          </button>
        </div>
    </div>
</div>

@endsection
