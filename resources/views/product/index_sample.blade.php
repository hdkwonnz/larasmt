@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-3">Products Feeders</h2>

    @foreach ($products as $product)
    <div class="row">
        <div class="col-md-3">
            <div>
                <h4>{{ $product->name }}</h4>                   
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h4>{{ $product->machine->name }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <h4>{{ $product->department->name }}</h4>
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
        <div class="col-md-1">
            {{ $feeder->feeder_number }}
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
</div>

@endsection
