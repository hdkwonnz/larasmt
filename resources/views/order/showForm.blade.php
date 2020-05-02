@extends('layouts.app')

@section('content')

<link href="/myCss/productFeederCreate.css" rel="stylesheet">

<div class="container mt-5">
    <order-create user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></order-create>
</div>

@endsection

@section('extra-js')
<!-- reset bootstrap modal when it gets closed -->
<script>
    $(".modal").on("hidden.bs.modal", function(){
        $('.modalMessage').html("").removeClass('alert').removeClass('alert-success');
        $('.modalError').html("").removeClass('alert').removeClass('alert-danger');
    });
</script>
@endsection
