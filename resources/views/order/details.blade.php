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
        <div class="col-md-2">
            <h2 class="blinking text-danger">
                Running
            </h2>
        </div>
        <div class="col-md-3">
            <h2 class="">
                Order#: {{ $order->order_number }}
            </h2>
        </div>
    </div>

    @foreach ($orderMerchines as $orderMerchine)
    <div class="row mt-3">
        <div class="col-md-5">
            <div>
                <h3 class="bg-warning">{{ $orderMerchine->machine_name }}</h4>
            </div>
        </div>
    </div>
    @php
    $orderFeeders = App\Orderfeeder::where('order_number','=',$orderMerchine->order_number)
        ->where('machine_id','=',$orderMerchine->machine_id)
        ->orderBy('feeder_number','asc')
        ->get();
    @endphp

    <!-- table -->
    <div class="row">
        <div class="table-responsive mt-1">
            <table class="table table-striped table-bordered table-sm">
                <tbody>
                    @foreach ($orderFeeders as $feeder)
                    <tr style="width: 15%">
                        <td>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="doRefill({{ $feeder->id }},{{ $feeder->feeder_number }},'{{ $feeder->position }}','{{ $orderMerchine->machine_name }}','{{ $feeder->own_partnumber }}')">
                                {{ $feeder->feeder_number }}
                            </button>
                            <span class="text-danger">{{ $feeder->position }}</span>
                        </td>
                        <td style="width: 10%" class="text-right">
                            {{ $feeder->qty }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->own_partnumber }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->vendor_partnumber }}
                        </td>
                        <td style="width: 25%">
                            {{ $feeder->value }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div><!-- end of table -->
    @endforeach
    <span>>>> end of page <<<</span>

    <!-- Modal -->
    <!-- to protect right click on mouse => oncontextmenu="return false;" -->
    <div oncontextmenu="return false;" class="modal fade refillModal-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="offset-md-5 col-md-3">
                        <h4 class="modal-title" id="exampleModalLabel">Refill Parts</h4>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body offset-md-2 col-md-10">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <h1 class="machine_name"></h1>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-md-2 col-md-10">
                            <span class="feeder_number display-4 bg-dark text-white"></span>
                            <span class="feeder_position display-4 bg-dark text-white"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ownPartNumber" class="col-md-2 col-form-label">Part Number</label>
                        <div class="col-md-6">
                            {{-- <span class="own_partnumber display-4"></span> --}}
                            <input id="ownPartNumber" type="text" class="form-control own_partnumber" value="" readonly>
                        </div>
                    </div>
                    <!-- form -->
                    <form id="refillForm" class="mt-4">
                        @csrf
                        <div class="form-group row">
                            <!-- take the value from jquery below-->
                            <input class="form-control form-control-alt" type="hidden" value="" id="feederId" name="feederId">
                        </div>
                        <div class="form-group row">
                            <label for="partNumber" class="col-md-2 col-form-label">Part Number</label>
                            <div class="col-md-6">
                                <input id="partNumber" type="text" class="form-control" name="partNumber" value="{{ old('partNumber') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row mt-5">
                            <div class="col-md-6 offset-md-2">
                                <button type="" class="btn btn-primary w-100">
                                    Click
                                </button>
                            </div>
                        </div>
                    </form>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
          </div>
        </div>
    </div> <!-- end of modal -->
</div> <!-- end of container -->

@endsection

@section('extra-js')
<!-- reset bootstrap modal when it gets closed -->
<script>
    $(".modal").on("hidden.bs.modal", function(){
        $('.feeder_number').html("");//clear field
        $('.feeder_position').html("");//clear field
        $('.machine_name').html("");//clear field
        $('.message').html("");//clear field
        $('.error').html("");//clear field
        $('#partNumber').val("");//clear field
        $('.own_partnumber').val("");//clear field
    });
</script>
<!-- take value from blade to modal -->
<script>
    function doRefill(feederId,feederNumber,feederPosition,machineName,ownPartNumber){
        $('.feeder_number').html("");//clear field
        $('.feeder_position').html("");//clear field
        $('.machine_name').html("");//clear field

        $('#feederId').val(feederId); //The id where to pass the value
        $('.feeder_number').append(feederNumber);//disply feeder number
        $('.feeder_position').append(feederPosition);//disply feeder position
        $('.machine_name').append(machineName);//disply feeder machine name
        $('.own_partnumber').val(ownPartNumber);//disply feeder ownPartNumber name

        $('.refillModal-modal-xl').modal('show'); //The class of the modal to show

        // for autofocus
        $(document).ready(function() {
            $('.refillModal-modal-xl').on('shown.bs.modal', function() {
            $('#partNumber').trigger('focus');
            });
        })
    }
</script>
<!-- send Form data to PostController -->
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
                scannedType: 'refill',
                feederId:feederId,
                partNumber:partNumber
            },
            success:function(response){
                //console.log(response);
                if (response == "good"){
                    $('.message').html("");//clear field
                    $('.error').html("");//clear field
                    $('.message').append("OK").css('color','blue')
                    $('#partNumber').val("")
                    setTimeout(() => {  $('.refillModal-modal-xl').modal('hide'); }, 1000);//auto hide
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
