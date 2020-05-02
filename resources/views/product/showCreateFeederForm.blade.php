@extends('layouts.admin')

@section('content')

<link href="/myCss/productFeederCreate.css" rel="stylesheet">

<div class="container mt-5">
    <feeder-create user_email="{{ auth()->user()->email }}" user_name="{{ auth()->user()->name }}"></feeder-create>
</div>

@endsection

@section('extra-js')
<!-- reset bootstrap modal when it gets closed -->
<script>
    $(".modal").on("hidden.bs.modal", function(){
        $('.message').html("");//clear field
        $('.error').html("");//clear field
    });
</script>
@endsection
