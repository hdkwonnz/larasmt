@extends('layouts.app')

@section('content')

<link href="/myCss/productFeederCreate.css" rel="stylesheet">

<div class="container mt-5">
    <user-index user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></user-index>
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
