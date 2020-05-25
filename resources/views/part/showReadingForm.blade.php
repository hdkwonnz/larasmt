@extends('layouts.app')

@section('content')

<link href="/myCss/productIndex.css" rel="stylesheet">
<link href="/myCss/wholeReading.css" rel="stylesheet">

<div class="container mt-5">
    <whole-reading user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></whole-reading>
</div>

@endsection

@section('extra-js')

@endsection
