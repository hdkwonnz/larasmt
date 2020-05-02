@extends('layouts.admin')

@section('content')

<link href="/myCss/productFeederCreate.css" rel="stylesheet">

<div class="container mt-5">
    <product-create user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></product-create>
</div>

@endsection

@section('extra-js')
<script>

</script>
@endsection
