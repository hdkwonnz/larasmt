@extends('layouts.admin')

@section('content')

<link href="/myCss/productFeederCreate.css" rel="stylesheet">

<div class="container mt-5">
    <part-create user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></part-create>
</div>

@endsection
