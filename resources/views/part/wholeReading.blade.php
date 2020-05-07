@extends('layouts.app')

@section('content')

<link href="/myCss/productIndex.css" rel="stylesheet">
<link href="/myCss/wholeReading.css" rel="stylesheet">

<div class="container mt-5">
    <!-- head -->
    <div class="row bg-success pt-2 top_menu">
        <div class="col-md-3">
            <h2 class="">
                {{ $orderFeeder->product_name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="">
                {{ $orderFeeder->department_name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="">
                {{ $orderFeeder->shift_name }}
            </h2>
        </div>
        <div class="col-md-2">
            <h2 class="blinking text-danger">
                Running
            </h2>
        </div>
        <div class="col-md-3">
            <h2 class="">
                Order#: {{ $orderFeeder->order_number }}
            </h2>
        </div>
    </div>

    <!-- Prev -->
    <div class="row mt-5">
        <div class="col-md-2">
            @if (isset($previous))
                <div class="alert-success pt-3">
                    <div class="text-center bg-success text-white">
                        <span class="">Previous #</span>
                    </div>
                    <div class="text-center mt-2">
                        <span class="">{{ $previous->machine_name }}</span>
                    </div>
                    <div class="display-4 text-center">
                        <span class="">{{ $previous->feeder_number }}</span>
                        <span>{{ $previous->position }}</span>
                    </div>
                    <!--  previous form -->
                    <form method="GET" action="{{ route('part.wholeReading')}}">
                        <input type="hidden" name="orderId" value="{{ $previous->id }}">
                        <input type="hidden" name="orderNumber" value="{{ $previous->order_number }}">
                        <div class="form-group row mt-5 pb-3">
                            <div class="offset-md-2 col-md-8">
                                <button type="submit" class="btn btn-primary w-100">
                                    Prev
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Current -->
        <!-- to protect right click on mouse => oncontextmenu="return false;" -->
        <div oncontextmenu="return false;" class="col-md-8">
            <div class="alert alert-light">
                <div class="text-center bg-dark text-white">
                    <span class="">Current #</span>
                </div>
                <div class="text-center mt-3">
                    <span class="machine_name display-4">{{ $orderFeeder->machine_name }}</span>
                </div>
                <div class="display-4 text-center">
                    <span class="">{{ $orderFeeder->feeder_number }}</span>
                    <span>{{ $orderFeeder->position }}</span>
                </div>
                <div class="form-group row">
                    <label for="ownPartNumber" class="col-md-2 col-form-label">Part Number</label>
                    <div class="col-md-10">
                        {{-- <span class="own_partnumber display-4"></span> --}}
                    <input id="ownPartNumber" type="text" class="form-control own_partnumber" value="{{ $orderFeeder->own_partnumber }}" readonly>
                    </div>
                </div>

                <!-- refill form -->
                <form id="refillForm" class="mt-4">
                    @csrf
                    <div class="form-group row">
                        <!-- take the value from jquery below-->
                        <input class="form-control form-control-alt" type="hidden" value="{{ $orderFeeder->id }}" id="feederId" name="feederId">
                    </div>
                    <div class="form-group row">
                        <label for="partNumber" class="col-md-2 col-form-label">Part Number</label>
                        <div class="col-md-10">
                            <input id="partNumber" type="text" class="form-control" name="partNumber" value="{{ old('partNumber') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row mt-5">
                        <div class="col-md-10 offset-md-2">
                            <button type="" class="btn btn-primary w-100">
                                Click
                            </button>
                        </div>
                    </div>
                </form>
                <!-- error message-->
                <div class="row">
                    <div class="offset-md-2 col-md-6 ">
                        <span class="message display-4"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="offset-md-2 col-md-6">
                        <span class="error display-4"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next -->
        <div class="col-md-2">
            @if (isset($next))
                <div class="alert-success pt-3">
                    <div class="text-center bg-danger text-white">
                        <span class="">Next #</span>
                    </div>
                    <div class="text-center mt-2">
                        <span class="">{{ $next->machine_name }}</span>
                    </div>
                    <div class="display-4 text-center">
                        <span class="">{{ $next->feeder_number }}</span>
                        <span>{{ $next->position }}</span>
                    </div>
                    <!-- next form -->
                    <form method="GET" action="{{ route('part.wholeReading')}}">
                        <input type="hidden" name="orderId" value="{{ $next->id }}">
                        <input type="hidden" name="orderNumber" value="{{ $next->order_number }}">
                        <div class="form-group row mt-5 pb-3">
                            <div class="offset-md-2 col-md-8">
                                <button type="submit" class="btn btn-primary next_button w-100">
                                    Next
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('extra-js')

<script>
    $('#refillForm').on('submit',function(event){
        event.preventDefault();

        feederId = $('#feederId').val().trim();
        partNumber = $('#partNumber').val().trim();
        $.ajax({
            url: "/refill",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                scannedType: 'whole',
                feederId: feederId,
                partNumber: partNumber
            },
            success:function(response){
                //console.log(response);
                if (response == "good"){
                    $('.message').html("");//clear field
                    $('.error').html("");//clear field
                    $('.message').append("OK").css('color','blue')
                    $('#partNumber').val("")
                    setTimeout(() => {  $('.refillModal-modal-xl').modal('hide'); }, 1000);//auto hide
                    $('.next_button').trigger('click');//go to next
                }else{
                    $('.message').html("");//clear field
                    $('.error').html("");//clear field
                    $('.error').append("NOK").css('color','red')
                }
            },
            error: function (data) {
                //console.log(data.statusText);
                $('.error').html("");//clear field
                $('.error').append(data.statusText).css('color','red')
            //debugger;
            }
        });
    });
</script>

@endsection
