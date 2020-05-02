@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="">
                <h4>Transaction Logs</h4>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('part.showLogs') }}" class="mt-4">
        @csrf
        <div class="form-group row">
            <label for="orderNmber" class="col-md-2 col-form-label">Order Numbers</label>
            <div class="col-md-10">
                <select name="orderId" id="orderId" required class="form-control dropdown-style input-field input-normal">
                    @foreach ($orders as $order)
                        <option value="{{ $order->id}}" >{{ $order->order_number }} - {{ $order->product_name }} - {{ $order->department_name }} - {{ $order->shift_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mt-5">
            <div class="col-md-6 offset-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    Click
                </button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-6 offset-md-2">
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-2">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
