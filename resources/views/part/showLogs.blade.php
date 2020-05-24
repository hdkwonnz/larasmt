@extends('layouts.app')

@section('content')

<link href="/myCss/orderDetails.css" rel="stylesheet">
<link href="/myCss/productIndex.css" rel="stylesheet">

<div class="container mt-5">
    @can('isEditor')
    <!-- this row will not appear when printing -->
    <!-- printme function is defined on resources/js/app.js-->
    <div class="row mt-3 no-print">
        <div class="col-xs-12">
            <a href="#" @click.prevent="printme" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    @endCan
    <!-- head -->
    <div class="row bg-success pt-2 top_menu">
        <div class="col-md-3">
            <h2 class="">
                {{ $order->product_name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="">
                {{ $order->department_name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="">
                {{ $order->shift_name }}
            </h2>
        </div>
        {{-- <div class="col-md-2">
            <h2 class="blinking text-danger">
                Running
            </h2>
        </div> --}}
        <div class="col-md-3">
            <h2 class="">
                Order#: {{ $order->order_number }}
            </h2>
        </div>
    </div>
    <!-- table -->
    <div class="row">
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>F/N</th>
                        <th>MACHINE</th>
                        <th>STATUS</th>
                        <th>ORIGINAL P/N</th>
                        <th>SCANNED P/N</th>
                        <th>TIME</th>
                        <th>TYPE</th>
                        <th>OPERATOR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                    <tr>
                    <td><strong>{{ $transaction->feeder_number }}</strong>{{ $transaction->position }}</td>
                        <td>{{ $transaction->machine_name }}</td>
                        @if ($transaction->scanned_result == 'failed')
                            <td>
                                <span class="text-danger">{{ $transaction->scanned_result }}</span>
                            </td>
                        @else
                            <td>
                                {{ $transaction->scanned_result }}
                            </td>
                        @endif
                        </td>
                        <td>{{ $transaction->own_partnumber }}</td>
                        <td>{{ $transaction->scanned_own_partnumber }}</td>
                        {{-- <td>{{ $transaction->created_at }}</td> --}}
                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s d-m-Y') }}</td>
                        <td>{{ $transaction->scanned_type }}</td>
                        <td>{{ $transaction->author }}({{ $transaction->user_id }})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> <!-- end table-responsive -->
        <span>>>> end of page <<<</span>
    </div>
</div> <!-- end container -->

@endsection

@section('extra-js')

@endsection
