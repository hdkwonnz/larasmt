@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-3">Part Refill</h1>
    <h2>Product Name: {{ $product->name }}</h2>
    <h2>Feeder NO: {{ $feeder->feeder_number }}</h2>
    <h2>Part NO: {{ $part->own_partnumber }}</h2>
    <h2>Machin: {{ $product->machine->name }}</h2>
    <h2>Machin: {{ $product->department->name }}</h2>

    <form action="{{ route('refill.update') }}" method="POST">
        @csrf
        <input type="text" name="partNumber" required autofocus>
        <input type="text" name="value" required>
        <button type="submit">Click</button>
    </form>
     
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>

@endsection
